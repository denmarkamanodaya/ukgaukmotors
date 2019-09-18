<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : NewsletterEmailService.php
 **/

namespace Quantum\newsletter\Services;

use Illuminate\Support\Facades\Mail;
use Quantum\base\Mail\General;

class NewsletterEmailService
{

    /**
     * @var NewsletterShortCodeService
     */
    private $newsletterShortCodeService;

    public function __construct(NewsletterShortCodeService $newsletterShortCodeService)
    {
        $this->newsletterShortCodeService = $newsletterShortCodeService;
    }

    public function sendEmail($email, $subscriber)
    {
        $data['to'] = $subscriber->email;
        $data['subject'] = $this->newsletterShortCodeService->subscriberEmail($email->subject, $subscriber);
        $data['content_html'] = $this->newsletterShortCodeService->subscriberEmail($email->html_message, $subscriber);
        $data['content_text'] = $this->newsletterShortCodeService->subscriberEmail($email->plain_message, $subscriber);
        $data['from'] = $subscriber->newsletter->email_from;
        $data['from_name'] = $subscriber->newsletter->email_from_name;
        $data['template'] = 'emails.'.$subscriber->newsletter->template->slug;

        if(config('main.newsletter_queue'))
        {
            /*Mail::queue('emails.'.$subscriber->newsletter->template->slug, $data, function($message) use($data)
            {
                $message->from($data['from'], $data['from_name']);
                $message->to($data['to']);
                $message->subject($data['subject']);
            });*/
            Mail::to($subscriber->email)->queue(new General($data));
        } else {
            /*Mail::send('emails.'.$subscriber->newsletter->template->slug, $data, function($message) use($data)
            {
                $message->from($data['from'], $data['from_name']);
                $message->to($data['to']);
                $message->subject($data['subject']);
            });*/
            Mail::to($subscriber->email)->send(new General($data));
        }

    }

}