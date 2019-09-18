<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMakeDescription extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_make_description';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'vehicle_make_id', 'content', 'featured_image'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehiclemake()
    {
        return $this->belongsTo('App\Models\VehicleMake');
    }
}
