<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModelVariantDescription extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_model_variant_description';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'vehicle_model_variant_id', 'content', 'featured_image'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehiclemodelvariant()
    {
        return $this->belongsTo('App\Models\VehicleModelVarient');
    }
}
