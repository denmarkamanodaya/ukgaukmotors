<?php

namespace Quantum\calendar\Models;

use Carbon\Carbon;
use \Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\Sluggable;

class Calendar extends Eloquent {

    use Sluggable;

    protected $fillable = ['user_id', 'title', 'slug', 'start_day', 'start_time', 'end_day', 'end_time',
        'repeat_type', 'repeat_year', 'repeat_month', 'repeat_day', 'repeat_week', 'repeat_weekday', 'status', 'repeat_amount', 'repeated', 'repeat_finished', 'tenant'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calendar_events';

    protected $dates = ['start_day', 'end_day'];

    protected $connection = 'mysql';

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(\Quantum\base\Models\User::class);
    }

    public function meta()
    {
        return $this->hasOne(\Quantum\calendar\Models\CalendarMeta::class, 'calendar_events_id');
    }

    public function cal_eventable()
    {
        return $this->morphTo();
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }

    public function categories()
    {
        return $this->belongsToMany(config('calendar.categoryClass'), config('calendar.categoryPivotName'));
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }
}
