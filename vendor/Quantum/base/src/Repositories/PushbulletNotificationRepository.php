<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : PushbulletNotificationRepository.php
 **/

namespace Quantum\base\Repositories;

use Quantum\base\Models\Emailing;
use Quantum\base\Services\UserShortcodeService;

class PushbulletNotificationRepository implements EventNotificationInterface
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

    public function sendSystemNotification($user)
    {
        if(\Settings::get('site_notification_pushbullet_api') != '')
        {
            $mailContent = Emailing::where('title', $this->message)->tenant()->firstOrFail();

            $data['subject'] = $this->userShortcodeService->membersEmail($mailContent->subject, $user->user);
            $data['content_text'] = $this->userShortcodeService->membersEmail($mailContent->content_text, $user->user, $user);
            $breaks = array("<br />","<br>","<br/>");
            $data['content_text'] = str_ireplace($breaks, "\r\n", $data['content_text']);
            return $this->send_push($data, \Settings::get('site_notification_pushbullet_api'));
        }
    }

    public function sendUserNotification($event)
    {
        if($event->user->userSetting->setting != '')
        {
            $mailContent = Emailing::where('title', $this->message)->tenant()->firstOrFail();

            $data['subject'] = $this->userShortcodeService->membersEmail($mailContent->subject, $event->user);
            $data['content_text'] = $this->userShortcodeService->membersEmail($mailContent->content_text, $event->user, $event);
            $breaks = array("<br />","<br>","<br/>");
            $data['content_text'] = str_ireplace($breaks, "\r\n", $data['content_text']);
            return $this->send_push($data, $event->user->userSetting->setting);
        }
    }

    private function send_push($data, $apiKey)
    {
    $data = array(
        'type' => 'note',
        'title' => $data['subject'],
        'body' => $data['content_text']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/pushes');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$apiKey
    ));

        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode>=200 && $httpcode<300) return $data;
        return false;
    }

    public function getErrors()
    {
        return $this->error;
    }



}