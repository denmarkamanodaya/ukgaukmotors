<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : EventNotificationInterface.php
 **/

namespace Quantum\base\Repositories;


interface EventNotificationInterface
{
    public function sendSystemNotification($user);

    public function sendUserNotification($user);
    
    public function setMessage($message);
    
    public function getErrors();
}