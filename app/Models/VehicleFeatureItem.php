<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class VehicleFeatureItem extends Model
{
    use Sluggable;
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_feature_items';

    protected $with = ['feature'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_features_id', 'name', 'slug', 'system', 'position', 'icon'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function feature()
    {
        return $this->belongsTo('App\Models\VehicleFeatures', 'vehicle_features_id');
    }

    public function vehicle()
    {
        return $this->belongsToMany('App\Models\Vehicles', 'vehicle_vehicle_feature_items', 'vehicle_feature_items_id', 'vehicle_id');
    }
}
