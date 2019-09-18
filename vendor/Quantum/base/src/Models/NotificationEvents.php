<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationEvents extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['event', 'emails_title', 'title', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notification_events';

    public function types()
    {
        return $this->belongsToMany(\Quantum\base\Models\NotificationTypes::class, 'notif_event_notif_type', 'notification_events_id', 'notification_types_id');
    }
    
    public function userSettings()
    {
        return $this->belongsToMany(\Quantum\base\Models\UserNotifications::class, 'user_event_notification', 'notification_events_id', 'user_notification_settings_id');
    }
}
