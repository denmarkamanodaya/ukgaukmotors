<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_make';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'system', 'country_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function models()
    {
        return $this->hasMany('App\Models\VehicleModel')->orderBy('name', 'ASC');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicles');
    }

    public function vehiclesCount()
    {
        return $this->hasOne('App\Models\Vehicles', 'vehicle_make_id')
            ->selectRaw('vehicle_make_id, count(*) as aggregate')
            ->groupBy('vehicle_make_id');
    }

    public function vehiclesCountByType($type)
    {
        return $this->hasOne('App\Models\Vehicles', 'vehicle_make_id')
            ->where('vehicle_listing_type', $type)
            ->selectRaw('vehicle_make_id, count(*) as aggregate')
            ->groupBy('vehicle_make_id');
    }

    public function description()
    {
        return $this->hasOne('App\Models\VehicleMakeDescription', 'vehicle_make_id');
    }

    public function country()
    {
        return $this->belongsTo('Quantum\base\Models\Countries');
    }
}
