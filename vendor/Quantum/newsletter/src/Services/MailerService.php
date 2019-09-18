<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : MailerService.php
 **/

namespace Quantum\newsletter\Services;


use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Quantum\base\Mail\General;
use Quantum\base\Models\User;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterMail;
use Quantum\newsletter\Models\NewsletterSentMail;
use Quantum\newsletter\Models\NewsletterSubscriber;

class MailerService
{

    protected $sent_to_amount = 0;
    protected $mailShot;
    protected $subscriber;
    protected $subscribers;
    protected $originalMessage = null;
    /**
     * @var NewsletterShortCodeService
     */
    private $newsletterShortCodeService;

    public function __construct(NewsletterShortCodeService $newsletterShortCodeService
    )
    {
        $this->newsletterShortCodeService = $newsletterShortCodeService;
    }

    public function getTimedMail($newsletter=null)
    {
        $timedMail = NewsletterSentMail::with(['newsletter' => function($query) {
            $query->with('mails');
        }])->SearchNewsletter($newsletter)->whereNotNull('send_on')->orderBy('send_on', 'ASC')->paginate(40);
        return $timedMail;
    }

    public function saveMailShot($request)
    {
        $html_message = $this->addUnsubscribe($request['html_message'], $request->newsletter);

        $bcc_to_email = null;
        if(!$request->personalise)
        {
            if(is_null($request->bcc_to_email))
            {
                $newsletter = Newsletter::where('id', $request->newsletter)->firstOrFail();
                $email_from = explode('@', $newsletter->email_from);
                $bcc_to_email = 'subscriber@'.$email_from[1];
            }
        }


        $mailshot = NewsletterSentMail::create([
            'newsletter_id' => $request->newsletter,
            'subject' => $request['subject'],
            'html_message' => $html_message,
            'plain_message' => strip_tags(nl2br($html_message)),
            'send_on' => Carbon::createFromFormat('F jS Y H:i', $request['send_on'])->toDateTimeString(),
            'mail_code' => str_random(40),
            'personalise' => isset($request->personalise) ? $request->personalise : null,
            'bcc_amount' => $request->bcc_amount,
            'bcc_to_email' => $bcc_to_email
        ]);

        flash('Newsletter Mail Shot has been created.')->success();
        \Activitylogger::log('Admin - Created Newsletter Mail Shot: '.$mailshot->subject, $mailshot);
    }

    public function updateMailShot($request, $id)
    {
        $mailshot = $this->getTimedMailById($id);

        $bcc_to_email = null;
        if(!$request->personalise)
        {
            if(is_null($request->bcc_to_email))
            {
                $newsletter = Newsletter::where('id', $request->newsletter)->firstOrFail();
                $email_from = explode('@', $newsletter->email_from);
                $bcc_to_email = 'subscriber@'.$email_from[1];
            } else {
                $bcc_to_email = $request->bcc_to_email;
            }
        }

        $mailshot->newsletter_id = $request->newsletter;
        $mailshot->subject = $request['subject'];
        $mailshot->html_message = $request['html_message'];
        $mailshot->plain_message = strip_tags(nl2br($request['html_message']));
        $mailshot->send_on = Carbon::createFromFormat('F jS Y H:i', $request['send_on'])->toDateTimeString();
        $mailshot->active = $request['active'];
        $mailshot->personalise = isset($request['personalise']) ? $request['personalise'] : null;
        $mailshot->bcc_amount = $request['bcc_amount'];
        $mailshot->bcc_to_email = $bcc_to_email;
        $mailshot->save();

        flash('Newsletter Mail Shot has been updated.')->success();
        \Activitylogger::log('Admin - Updated Newsletter Mail Shot: '.$mailshot->subject, $mailshot);
    }

    public function getTimedMailById($id)
    {
        $timedMail = NewsletterSentMail::with(['newsletter' => function($query) {
            $query->with('mails');
        }])->where('id', $id)->whereNotNull('send_on')->firstOrFail();
        return $timedMail;
    }

    public function deleteMailShot($id)
    {
        $mailshot = $this->getTimedMailById($id);
        $mailshot->delete();
        flash('Newsletter Mail Shot has been deleted.')->success();
        \Activitylogger::log('Admin - Deleted Newsletter Mail Shot: '.$mailshot->subject, $mailshot);
    }

    public function getSentMail($newsletter=null)
    {
        $timedMail = NewsletterSentMail::with('newsletter')->SearchNewsletter($newsletter)->whereNotNull('sent_on')->orderBy('sent_on', 'DESC')->paginate(40);
        return $timedMail;
    }

    public function getSentMailById($id)
    {
        $timedMail = NewsletterSentMail::with('newsletter')->where('id', $id)->whereNotNull('sent_on')->firstOrFail();
        return $timedMail;
    }

    public function injectTrackingPixel($html, $hash, $personalise=null)
    {
        // Append the tracking url
        if(!$personalise) {
            $tracking_pixel = '<img border=0 width=1 alt="" height=1 src="'.url('/newsletter/mailerimage/'.$hash).'" />';
        } else {
            $tracking_pixel = '<img border=0 width=1 alt="" height=1 src="'.url('/newsletter/mailerimage/'.$hash.'/[subCode]').'" />';
        }
        $linebreak = str_random(32);
        $html = str_replace("\n",$linebreak,$html);
        if(preg_match("/^(.*<body[^>]*>)(.*)$/", $html, $matches)) {
            $html = $matches[1].$matches[2].$tracking_pixel;
        } else {
            $html = $html . $tracking_pixel;
        }
        $html = str_replace($linebreak,"\n",$html);
        return $html;
    }

    private function addUnsubscribe($message, $newsletter_id=null)
    {
        if($newsletter_id)
        {
            $unsub_message = NewsletterMail::where('newsletter_id', $newsletter_id)->where('message_type', 'unsubscribe_email')->orderBy('position')->first();
        } else {
            $unsub_message = $this->mailShot->newsletter->mails->where('message_type', 'unsubscribe_email')->sortBy('position')->first();
        }
        if($unsub_message)
        {
            if($unsub_message->html_message == '' || is_null($unsub_message->html_message)) return $message;
            $message = $message.'<br>'.$unsub_message->html_message;
        }
        return $message;
    }

    public function sendMailShotsBatched()
    {
        $limit = config('newsletter.newsletter_batch');
        $mailshot = NewsletterSentMail::with(['newsletter' => function($query) {
            $query->withCount('subscribersActive')->with('mails', 'shotTemplate');
        }])->whereNotNull('send_on')->whereDate('send_on', '<=', Carbon::now())->where('in_progress', 0)->where('active', 1)->orderBy('send_on', 'DESC')->limit(1)->first();
        if(!$mailshot) return;
        if($mailshot->newsletter->subscribers_active_count == 0) return;
        $this->originalMessage = $mailshot->html_message;
        $mailshot->in_progress = 1;
        $mailshot->save();
        $this->sent_to_amount = $mailshot->sent_count;
        $this->subscribers = NewsletterSubscriber::with('user')->where('newsletter_id', $mailshot->newsletter->id)
            ->where('email_confirmed', 1)->where('active', 1)->orderBy('id', 'ASC')->skip($mailshot->sent_count)->take($limit)->get();
        //dd($mailshot, $this->sent_to_amount, $limit, $mailshot->subscribersActive);
        if(!$this->subscribers) return $this->completeSend();
        $this->mailShot = $mailshot;
        $this->sendShot();
    }

    public function sendMailShots()
    {
        $mailShots = NewsletterSentMail::with(['newsletter' => function($query) {
            $query->with('subscribersActive', 'mails', 'template');
        }])->whereNotNull('send_on')->whereDate('send_on', '<=', Carbon::now())->where('active', 1)->orderBy('send_on', 'DESC')->get();

        if($mailShots)
        {
            foreach ($mailShots as $mailShot)
            {
                $this->mailShot = $mailShot;
                $this->originalMessage = $mailShot->html_message;
                $this->sendShot();
            }
        }
    }

    private function sendShot()
    {
        $this->mailShot->html_message = $this->injectTrackingPixel($this->mailShot->html_message, $this->mailShot->mail_code);
        //$this->mailShot->html_message = $this->addUnsubscribe($this->mailShot->html_message);
        if($this->subscribers->count() < 1) return $this->completeSend();

        //if not personalise and has bcc
        //chunk into bcc limit
        //loop through chunks set all to be bcc of bcc to address
        if(!$this->mailShot->personalise && $this->mailShot->bcc_amount && $this->mailShot->bcc_amount > 0) {
            //bcc send
            $subscribersChunked = $this->subscribers->chunk($this->mailShot->bcc_amount);

            //loop chunks
            foreach ($subscribersChunked as $subscribers)
            {
                $bcc = [];
                $mainSubscriber = new NewsletterSubscriber();
                $mainSubscriber->email = $this->mailShot->bcc_to_email;
                if(is_null($mainSubscriber->email) || $mainSubscriber->email == '') return $this->completeSend();
                //loop subscribers in chunk

                foreach ($subscribers as $subscriber)
                {
                    array_push($bcc, $subscriber->email);
                }
                $mainSubscriber->bcc = $bcc;
                $this->sendMail($mainSubscriber);
            }

        } else {
            //normal send
            foreach($this->subscribers as $subscriber)
            {
                $this->sendMail($subscriber);
            }
        }

        $this->completeSend();
    }

    private function completeSend()
    {
        $sentAll = false;
        if($this->sent_to_amount >= $this->mailShot->newsletter->subscribers_active_count) $sentAll = true;
        $this->mailShot->sent_count = $this->sent_to_amount;
        $this->mailShot->in_progress = 0;

        if($sentAll)
        {
            $this->mailShot->send_on = null;
            $this->mailShot->sent_on = Carbon::now()->toDateTimeString();
        }
        $this->mailShot->html_message = $this->originalMessage; //reset changes done to html message
        $this->mailShot->save();
        return true;
    }

    private function sendMail($subscriber)
    {
        if($this->mailShot->personalise)
        {
            $data['subject'] = $this->newsletterShortCodeService->subscriberEmail($this->mailShot->subject, $subscriber);
            $data['content_html'] = $this->newsletterShortCodeService->subscriberEmail($this->mailShot->html_message, $subscriber);
            $data['content_text'] = $this->newsletterShortCodeService->subscriberEmail($this->mailShot->plain_message, $subscriber);
        } else {
            $data['subject'] = $this->newsletterShortCodeService->justClean($this->mailShot->subject);
            $data['content_html'] = $this->newsletterShortCodeService->justClean($this->mailShot->html_message);
            $data['content_text'] = $this->newsletterShortCodeService->justClean($this->mailShot->plain_message);
        }

        $data['from'] = $this->mailShot->newsletter->email_from;
        $data['from_name'] = $this->mailShot->newsletter->email_from_name;
        if(isset($this->mailShot->newsletter->shotTemplate)) {
            $data['template'] = 'emails.'.$this->mailShot->newsletter->shotTemplate->slug;
        } else {
            $data['template'] = 'emails.'.$this->mailShot->newsletter->template->slug;
        }

        $bcc = isset($subscriber->bcc) ? $subscriber->bcc : [];
        if(config('main.newsletter_queue'))
        {
            $message = (new General($data))
                ->onQueue('newsletter');
            Mail::to($subscriber->email)->bcc($bcc)->queue($message);
        } else {

            Mail::to($subscriber->email)->send(new General($data));
        }

        if($this->mailShot->personalise)
        {
            $this->sent_to_amount ++;
        } else {
            if(count($bcc) > 0) $this->sent_to_amount = $this->sent_to_amount + count($bcc);
        }
    }

    public function sendResponders()
    {
        $subscribers = NewsletterSubscriber::with(['user','newsletter' => function($query) {
            $query->with('responders', 'mails', 'template');
        }])->where('sequence', '!=', 0)->whereDate('sequence_send_on', '<=', Carbon::now())->where('email_confirmed', 1)->get();

        if($subscribers->count() < 1) return;

        foreach ($subscribers as $this->subscriber)
        {
            $responder = $this->subscriber->newsletter->responders->where('position', $this->subscriber->sequence)->first();
            if(!$responder) return $this->resetSubscriber();
            $this->mailShot = $responder;
            //todo track responder opening
            //$this->mailShot->html_message = $this->injectTrackingPixel($this->mailShot->html_message, $this->mailShot->mail_code);
            //
            $this->mailShot->newsletter = $this->subscriber->newsletter;
            $this->mailShot->html_message = $this->addUnsubscribe($this->mailShot->html_message);
            $this->sendMail($this->subscriber);
            $this->nextResponder();
        }
        $user = User::where('id', 1)->first();
        if($this->sent_to_amount > 0)\Activitylogger::log('Cron - Sent Responder Followups : '.$this->sent_to_amount, null, $user);
    }

    private function resetSubscriber()
    {
        $this->subscriber->sequence = 0;
        $this->subscriber->sequence_send_on = null;
        $this->subscriber->save();
        return true;
    }

    private function nextResponder()
    {
        $responder = $this->subscriber->newsletter->responders->where('position', $this->subscriber->sequence +1)->first();
        if(!$responder) return $this->resetSubscriber();
        if($responder->interval_type == 'Minutes') $this->subscriber->sequence_send_on = Carbon::now()->addMinutes($responder->interval_amount);
        if($responder->interval_type == 'Hours') $this->subscriber->sequence_send_on = Carbon::now()->addHours($responder->interval_amount);
        if($responder->interval_type == 'Days') $this->subscriber->sequence_send_on = Carbon::now()->addDays($responder->interval_amount);
        if($responder->interval_type == 'Weeks') $this->subscriber->sequence_send_on = Carbon::now()->addWeeks($responder->interval_amount);
        if($responder->interval_type == 'Months') $this->subscriber->sequence_send_on = Carbon::now()->addMonths($responder->interval_amount);
        if($responder->interval_type == 'Years') $this->subscriber->sequence_send_on = Carbon::now()->addYears($responder->interval_amount);
        $this->subscriber->sequence = $this->subscriber->sequence + 1;
        $this->subscriber->save();
        return true;
    }

    public function logOpen($id, $subcode=null)
    {
        $cacheOpen = Cache::get('news_open_'.$id);
        if(!$cacheOpen) $cacheOpen = [];
        if($subcode) {
            if(!in_array($subcode, $cacheOpen)) array_push($cacheOpen, $subcode);
        } else {
            array_push($cacheOpen, 1);
        }
        Cache::forever('news_open_'.$id, $cacheOpen);
    }

    public function countOpened()
    {
        $sentMails = NewsletterSentMail::get();
        foreach ($sentMails as $sentMail)
        {
            if($cacheOpen = Cache::get('news_open_'.$sentMail->mail_code))
            {
                Cache::forget('news_open_'.$sentMail->mail_code);
                $count = count($cacheOpen);
                $sentMail->opened_count = $sentMail->opened_count + $count;
                $sentMail->save();
            }
        }
    }



}