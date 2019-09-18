<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleEngineSize extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_engine_size';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_type_id', 'slug', 'size', 'position'];


    public function type()
    {
        return $this->belongsTo('App\Models\VehicleType', 'vehicle_type_id');
    }
}
