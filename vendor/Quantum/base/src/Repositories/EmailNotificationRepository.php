<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : EmailNotificationRepository.php
 **/

namespace Quantum\base\Repositories;

use Quantum\base\Services\EmailService;
use Quantum\base\Services\UserShortcodeService;

class EmailNotificationRepository implements EventNotificationInterface
{

    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var UserShortcodeService
     */
    private $userShortcodeService;
    
    private $message;

    protected $error;

    public function __construct(EmailService $emailService, UserShortcodeService $userShortcodeService)
    {
        $this->emailService = $emailService;
        $this->userShortcodeService = $userShortcodeService;
    }

    public function sendSystemNotification($event)
    {
        if(\Settings::get('site_notification_email') != '')
        {
            $data['to'] = \Settings::get('site_notification_email');
            $data['mail_template'] = $this->message;
            $this->emailService->send_system_mail($event->user, $data, null, $event);
            return true;
        }
        return false;
    }

    public function sendUserNotification($event)
    {
        if($event->user->userSetting->setting != '')
        {
            $data['to'] = $event->user->userSetting->setting;
            $data['mail_template'] = $this->message;
            $this->emailService->send_system_mail($event->user, $data, null, $event);
            return true;
        }
        return false;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getErrors()
    {
        return $this->error;
    }
}