<?php


/**
 * Set notification image
 * @param $type
 * @return string
 */
function notif_image($type)
{
    $image = '';
    switch ($type) {
        case "info":
            $image = 'far fa-info-circle';
            break;
        case "success":
            $image = 'far  fa-check-circle';
            break;
        case "danger":
            $image = 'far  fa-times-circle';
            break;
        case "warning":
            $image = 'far fa-exclamation-triangle';
            break;
        default:
            $image = '';
    }
    return $image;
}

function notifactionSetting($notificaton, $notifications)
{
    foreach($notifications as $userNotification)
    {
        if($userNotification->notification_types_id == $notificaton->id) return $userNotification->setting;
    }
    return false;
}