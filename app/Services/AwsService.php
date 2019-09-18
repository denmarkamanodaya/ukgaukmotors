<?php

namespace App\Services;


use App\Models\BounceLog;
use Aws\Sqs\SqsClient;
use Quantum\newsletter\Models\NewsletterSubscriber;

class AwsService
{

    /**
     * @var null
     */
    private $sqs = null;

    /**
     * @var null
     */
    private $data = null;

    /**
     * Process SQS bounces
     */
    public function processBounces()
    {
        $this->connect();
        $this->receiveMessages(config('services.ses.bounce'));
        if(is_null($this->data)) exit;
        $this->handleBounceMessages();
    }

    /**
     * Process SQS Complaints
     */
    public function processComplaints()
    {
        $this->connect();
        $this->receiveMessages(config('services.ses.complaint'));
        if(is_null($this->data)) exit;
        $this->handleComplaintMessages();
    }

    /**
     * Setup SQS Connection
     */
    private function connect()
    {
        if(!is_null($this->sqs)) return;
        if(is_null(config('services.ses.key'))) abort(403, 'SES_KEY is not set in .env file');
        if(is_null(config('services.ses.secret'))) abort(403, 'SES_SECRET is not set in .env file');
        if(is_null(config('services.ses.region'))) abort(403, 'SES_REGION is not set in .env file');
        if(is_null(config('services.ses.bounce'))) abort(403, 'SES_BOUNCE_URL is not set in .env file');
        if(is_null(config('services.ses.complaint'))) abort(403, 'SES_COMPLAINT_URL is not set in .env file');

        $this->sqs = SqsClient::factory(array(
            'credentials' => [
                'key' => config('services.ses.key'),
                'secret' => config('services.ses.secret'),
            ],
            'region'  => config('services.ses.region'),
            'version'  => 'latest',
        ));
    }


    /**
     * get SQS messages
     *
     * @param $sqsUrl
     * @return array
     */
    private function receiveMessages($sqsUrl)
    {
        $data = $this->sqs->receiveMessage([
            'QueueUrl' => $sqsUrl
        ]);
        $this->data = $data->search('Messages');
    }

    /**
     * Delete a SQS message
     *
     * @param $sqsUrl
     * @param $ReceiptHandle
     */
    private function deleteMessage($url, $ReceiptHandle)
    {
        $this->sqs->deleteMessage([
            'QueueUrl' => $url,
            'ReceiptHandle' => $ReceiptHandle,
        ]);
    }

    /**
     * Handle the bounced message
     */
    private function handleBounceMessages()
    {
        foreach ($this->data as $message) {
            $messageBody = json_decode($message['Body']);
            $messageMessage = json_decode($messageBody->Message);

            foreach($messageMessage->bounce->bouncedRecipients as $recipient)
            {
                if(!$recipient->emailAddress) continue;

                $bounced = BounceLog::where('email', $recipient->emailAddress)->count();
                if($bounced == 0)
                {
                    BounceLog::create([
                        'email' => $recipient->emailAddress,
                        'type' => $messageMessage->bounce->bounceType,
                        'details' => isset($recipient->diagnosticCode) ? $recipient->diagnosticCode : null
                    ]);
                }


                //if($messageMessage->bounce->bounceType != 'Transient')
                //{
                    NewsletterSubscriber::where('email', $recipient->emailAddress)->update([
                        'bounced' => 1,
                        'active' => 0
                    ]);
                //}

            }

            // done with message so we delete it from queue
           $this->deleteMessage(config('services.ses.bounce'), $message['ReceiptHandle']);
        }
        // kinda dirty loop ???
       $this->processBounces();
    }

    /**
     * Handle the complain message
     */
    private function handleComplaintMessages()
    {
        foreach ($this->data as $message) {
            $messageBody = json_decode($message['Body']);
            $messageMessage = json_decode($messageBody->Message);

            foreach($messageMessage->complaint->complainedRecipients as $recipient)
            {
                if(!$recipient->emailAddress) continue;
                $bounced = BounceLog::where('email', $recipient->emailAddress)->count();

                if($bounced == 0)
                {
                    BounceLog::create([
                        'email' => $recipient->emailAddress,
                        'type' => 'Complaint',
                        'details' => ''
                    ]);
                }


                NewsletterSubscriber::where('email', $recipient->emailAddress)->update([
                    'complaint' => 1,
                    'active' => 0
                ]);


            }

            // done with message so we delete it from queue
            $this->deleteMessage(config('services.ses.complaint'), $message['ReceiptHandle']);
        }
        // kinda dirty loop ???
        $this->processComplaints();
    }

    private function cleanTransient()
    {
        $transients = BounceLog::where('type', 'Transient')->get();
        foreach ($transients as $transient)
        {
            NewsletterSubscriber::where('email', $transient->email)->update([
                'bounced' => 1,
                'active' => 0
            ]);
        }
    }

}