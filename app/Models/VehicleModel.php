<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_model';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'vehicle_make_id', 'system'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function make()
    {
        return $this->belongsTo('App\Models\VehicleMake', 'vehicle_make_id');
    }

    public function description()
    {
        return $this->hasOne('App\Models\VehicleModelDescription', 'vehicle_model_id');
    }

    public function variants()
    {
        return $this->hasMany('App\Models\VehicleModelVarient')->orderBy('model_desc');;
    }

    public function vehiclesCount()
    {
        return $this->hasOne('App\Models\Vehicles', 'vehicle_model_id')
            ->selectRaw('vehicle_model_id, count(*) as aggregate')
            ->groupBy('vehicle_model_id');
    }
}
