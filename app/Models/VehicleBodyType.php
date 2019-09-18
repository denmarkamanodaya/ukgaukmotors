<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBodyType extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_body_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_type_id', 'slug', 'name', 'position'];


    public function type()
    {
        return $this->belongsTo('App\Models\VehicleType', 'vehicle_type_id');
    }
}
