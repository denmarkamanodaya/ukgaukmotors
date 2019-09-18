<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleListingType extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_listing_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'system'];

    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicles');
    }


}
