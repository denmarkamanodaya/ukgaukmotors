<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclesMedia extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_id', 'name', 'type', 'status', 'default_image', 'remote_type', 'remote_lot'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicles');
    }
}
