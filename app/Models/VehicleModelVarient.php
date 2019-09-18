<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModelVarient extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_model_varient';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_model_id', 'model_platform', 'model_name', 'model_desc', 'source', 'year_sold',
        'location', 'classification', 'body_type', 'doors', 'seats', 'engine_place', 'drivetrain', 'cylinders',
        'displacement', 'power_ps', 'power_kw', 'power_rpm', 'torque_nm', 'torque_rpm', 'bore_stroke', 'compression_ration',
        'valves_cylinder', 'crankshaft', 'fuel_injection', 'supercharged', 'catalytic', 'manual', 'automatic', 'suspension_front',
        'suspension_rear', 'assisted_steering', 'brakes_front', 'brakes_rear', 'abs', 'esp', 'tire_size', 'tire_size_rear',
        'wheel_base', 'track_front', 'track_rear', 'length', 'width', 'height', 'curb_weight', 'gross_weight', 'cargo_space',
        'tow_weight', 'gas_tank', 'zero_hundred', 'max_speed', 'fuel_eff', 'engine_type', 'fuel_type', 'co2', 'system', 'default'];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehiclemodel()
    {
        return $this->belongsTo('App\Models\VehicleModel', 'vehicle_model_id');
    }

    public function description()
    {
        return $this->hasOne('App\Models\VehicleModelVariantDescription', 'vehicle_model_variant_id');
    }
}
