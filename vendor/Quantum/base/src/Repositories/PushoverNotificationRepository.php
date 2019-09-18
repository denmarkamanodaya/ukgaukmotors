<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : PushoverNotificationRepository.php
 **/

namespace Quantum\base\Repositories;

use Quantum\base\Models\Emailing;
use Quantum\base\Services\UserShortcodeService;

class PushoverNotificationRepository implements EventNotificationInterface
{
    /**
     * @var UserShortcodeService
     */
    private $userShortcodeService;

    protected $pb_url = 'https://api.pushbullet.com/v2/';

    protected $message;

    protected $error;


    public function __construct(UserShortcodeService $userShortcodeService)
    {
        $this->userShortcodeService = $userShortcodeService;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function sendSystemNotification($event)
    {
        if((\Settings::get('site_notification_pushover_token') != '') && (\Settings::get('site_notification_pushover_user') != ''))
        {
            $mailContent = Emailing::where('title', $this->message)->tenant()->firstOrFail();

            $data['subject'] = $this->userShortcodeService->membersEmail($mailContent->subject, $event->user, $event);
            $data['content_text'] = $this->userShortcodeService->membersEmail($mailContent->content_text, $event->user, $event);
            $breaks = array("<br />","<br>","<br/>");
            $data['content_text'] = str_ireplace($breaks, "\r\n", $data['content_text']);
            return $this->send_push($data, \Settings::get('site_notification_pushover_token'), \Settings::get('site_notification_pushover_user'));
        }
    }

    public function sendUserNotification($event)
    {
        if(($event->user->userSetting->setting != '') && (\Settings::get('site_notification_pushover_token') != ''))
        {
            $mailContent = Emailing::where('title', $this->message)->tenant()->firstOrFail();

            $data['subject'] = $this->userShortcodeService->membersEmail($mailContent->subject, $event->user, $event);
            $data['content_text'] = $this->userShortcodeService->membersEmail($mailContent->content_text, $event->user, $event);
            $breaks = array("<br />","<br>","<br/>");
            $data['content_text'] = str_ireplace($breaks, "\r\n", $data['content_text']);
            return $this->send_push($data, \Settings::get('site_notification_pushover_token'), $event->user->userSetting->setting);
        }
    }

    public function getErrors()
    {
        return $this->error;
    }

    private function send_push($data, $apiToken, $userKey){
        $data = array(
            'token' => $apiToken,
            'user' => $userKey,
            'title' => $data['subject'],
            'message' => $data['content_text']
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushover.net/1/messages.json');
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        if ($httpcode>=200 && $httpcode<300) return $data;
        $this->decodeData($data);
        return false;
    }

    private function decodeData($data)
    {
        $data = json_decode($data);
        if(isset($data['errors'])) $this->error = $data['errors'];
    }
}