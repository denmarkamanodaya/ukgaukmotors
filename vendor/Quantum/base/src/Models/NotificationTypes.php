<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTypes extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'allow_members', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notification_types';

    public function events()
    {
        return $this->belongsToMany(\Quantum\base\Models\NotificationEvents::class, 'notif_event_notif_type', 'notification_types_id', 'notification_events_id');
    }
}
