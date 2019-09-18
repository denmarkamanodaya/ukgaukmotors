<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleLocation extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_id', 'address', 'address2', 'city', 'county', 'postcode', 'country', 'phone',
        'email', 'website', 'longitude', 'latitude'];



    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicles');
    }
}
