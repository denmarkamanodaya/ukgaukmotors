<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class VehicleFeatures extends Model
{
    use Sluggable;

    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'system', 'position', 'icon'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function items()
    {
        return $this->hasMany('App\Models\VehicleFeatureItem');
    }
}
