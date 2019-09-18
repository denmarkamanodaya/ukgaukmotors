<?php

namespace Quantum\calendar\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class CalendarMeta extends Eloquent {

    protected $fillable = ['calendar_events_id', 'description', 'address', 'county', 'country_id', 'postcode',
        'latitude', 'longitude', 'event_image', 'event_url'];

    protected $relatedObject = null;

    protected $connection = 'mysql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calendar_events_meta';

    public function calendar()
    {
        return $this->belongsTo(\Quantum\calendar\Models\Calendar::class);
    }

    public function country()
    {
        return $this->belongsTo(\Quantum\base\Models\Countries::class);
    }
}
