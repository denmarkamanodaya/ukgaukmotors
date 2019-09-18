<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserNotifications.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotifications extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'notification_types_id', 'setting'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_notification_settings';

    public function user()
    {
        return $this->belongsTo(\Quantum\base\Models\User::class);
    }

    public function notificationType()
    {
        return $this->belongsTo(\Quantum\base\Models\NotificationTypes::class, 'notification_types_id');
    }
    
    public function event()
    {
        return $this->belongsToMany(\Quantum\base\Models\NotificationEvents::class, 'user_event_notification', 'user_notification_settings_id', 'notification_events_id');
    }
}