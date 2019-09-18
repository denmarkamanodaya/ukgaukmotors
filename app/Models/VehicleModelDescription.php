<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModelDescription extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_model_description';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'vehicle_model_id', 'content', 'featured_image'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehiclemodel()
    {
        return $this->belongsTo('App\Models\VehicleModel');
    }
}
