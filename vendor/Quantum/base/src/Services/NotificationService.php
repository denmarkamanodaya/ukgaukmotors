<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : NotificationService.php
 **/

namespace Quantum\base\Services;


use Quantum\base\Services\Settings;
use Quantum\base\Models\NotificationEvents;
use Quantum\base\Models\NotificationTypes;
use Quantum\base\Models\UserNotifications;

class NotificationService
{

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function updateSettings($request)
    {
        $this->settings->updateSetting('site_notification_email', isset($request['site_notification_email']) ? $request['site_notification_email'] : '');
        $this->settings->updateSetting('site_notification_pushbullet_api', isset($request['site_notification_pushbullet_api']) ? $request['site_notification_pushbullet_api'] : '');
        $this->settings->updateSetting('site_notification_pushover_token', isset($request['site_notification_pushover_token']) ? $request['site_notification_pushover_token'] : '');
        $this->settings->updateSetting('site_notification_pushover_user', isset($request['site_notification_pushover_user']) ? $request['site_notification_pushover_user'] : '');
        $this->settings->clearCache();
        
        $Notif_events = NotificationEvents::get();
        foreach ($Notif_events as $notif_event)
        {
            $event_notifications = str_replace('.', '_', $notif_event->event).'_type';

            if(isset($request[$event_notifications]))
            {
                $notif_event->types()->sync($request[$event_notifications]);
            } else {
                $notif_event->types()->detach();
            }
        }

        $notifications = NotificationTypes::all();
        foreach ($notifications as $notification)
        {
            if(in_array($notification->id, $request->allow_members ?:[]))
            {
                $notification->allow_members = true;
            } else {
                $notification->allow_members = false;
            }
            $notification->save();
        }

        flash('Success : Notification Settings has been updated.')->success();
    }
    
    public function processEvent($event, $notifType, $notifEvent)
    {
        $class = 'Quantum\base\Repositories\\'.ucfirst($notifType->slug).'NotificationRepository';

        $notification = \App::make($class);
        $notification->setMessage($notifEvent->emails_title);
        $sent = $notification->sendSystemNotification($event);
        if(!$sent) $this->getErrors($notification, $notifType);
    }

    private function getErrors($notification, $notifType, $event=null)
    {
        $errors = $notification->getErrors();
        if(is_array($errors))
        {
            foreach ($errors as $error)
            {
                if($error == 'user identifier is invalid') $this->clearSetting($notifType, $event, 'user');
            }
        } else {
            if($errors == 'user identifier is invalid') $this->clearSetting($notifType, $event, 'user');
        }
    }

    private function clearSetting($notifType, $event=null, $arrkey=null)
    {
        if($event)
        {
            UserNotifications::where('user_id', $event->user->id)->where('notification_types_id', $notifType->id)->update(['setting', '']);
        } else {
            $settings = [
                'email' => 'site_notification_email',
                'pushbullet' => 'site_notification_pushbullet_api',
                'pushover' => ['token' =>'site_notification_pushover_token', 'user' =>'site_notification_pushover_user'],
            ];

            if($arrkey)
            {
                if(!isset($settings[$notifType->slug][$arrkey])) return;
                $key = $settings[$notifType->slug][$arrkey];
            } else {
                if(!isset($settings[$notifType->slug])) return;
                $key = $settings[$notifType->slug];
            }
            $this->settings->updateSetting($key, '');
            $this->settings->clearCache();
        }



    }

    public function eventCaptured($event, $eventType)
    {
        if($notifEvent = NotificationEvents::with('types')->where('event', $eventType)->first())
        {
            if($notifEvent->types->count() > 0)
            {
                foreach ($notifEvent->types as $type)
                {
                    $this->processEvent($event, $type, $notifEvent);
                }
            }
        }
    }


    public function processUserEvent($event, $notifType, $notifEvent)
    {
        $class = 'Quantum\base\Repositories\\'.ucfirst($notifType->slug).'NotificationRepository';

        $notification = \App::make($class);
        $notification->setMessage($notifEvent->emails_title);
        $sent = $notification->sendUserNotification($event);
        if(!$sent) $this->getErrors($notification, $notifType, $event);
    }

    public function userEventCaptured($event, $eventType)
    {

        if(isset($event->user->userSettings))
        {
            $notifEvent = NotificationEvents::where('event', $eventType)->first();
            if($event->user->userSettings->count() > 0)
            {
                foreach ($event->user->userSettings as $userSetting)
                {
                    if($userSetting->notificationType->allow_members != '1') continue;
                    $event->user->userSetting = $userSetting;
                    $this->processUserEvent($event, $userSetting->notificationType, $notifEvent);
                }
            }
        } else {
            if($notifEvent = NotificationEvents::with(['userSettings' => function ($query) use($event) {
                $query->with('notificationType')->where('user_id', $event->user->id);
            }])->where('event', $eventType)->first())
            {
                if($notifEvent->userSettings->count() > 0)
                {
                    foreach ($notifEvent->userSettings as $userSetting)
                    {
                        if($userSetting->notificationType->allow_members != '1') continue;
                        $event->user->userSetting = $userSetting;
                        $this->processUserEvent($event, $userSetting->notificationType, $notifEvent);
                    }
                }

            }
        }
        
    }

}