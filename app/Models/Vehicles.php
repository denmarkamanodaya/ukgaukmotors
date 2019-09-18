<?php

namespace App\Models;

use App\Filters\Filterable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use Filterable;
    use Sluggable;
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dealer_id', 'url', 'name', 'description', 'price', 'mileage', 'colour', 'gearbox', 'fuel', 'engine_size',
        'vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'auction_date', 'expire_date', 'status', 'registration',
    'mot','estimate','co2','type','service_history','additional_info', 'slug', 'vehicle_listing_type', 'user_id', 'vehicle_variant_id',
    'vehicle_engine_size_id', 'vehicle_body_type_id', 'county', 'match_attempt'];

    protected $dates = ['auction_date', 'expire_date'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Models\VehiclesMedia', 'vehicle_id')->orderBy('default_image', 'DESC')->orderBy('id', 'ASC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function make()
    {
        return $this->belongsTo('App\Models\VehicleMake', 'vehicle_make_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model()
    {
        return $this->belongsTo('App\Models\VehicleModel', 'vehicle_model_id');
    }

    public function variant()
    {
        return $this->belongsTo('App\Models\VehicleModelVarient', 'vehicle_variant_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicleType()
    {
        return $this->belongsTo('App\Models\VehicleType', 'vehicle_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealers');
    }

    public function listingType()
    {
        return $this->belongsTo('App\Models\VehicleListingType');
    }

    public function features()
    {
        return $this->belongsToMany('App\Models\VehicleFeatureItem', 'vehicle_vehicle_feature_items', 'vehicle_id', 'vehicle_feature_items_id');
    }

    public function location()
    {
        return $this->hasOne('App\Models\VehicleLocation', 'vehicle_id');
    }

    public function engineSize()
    {
        return $this->belongsTo('App\Models\VehicleEngineSize', 'vehicle_engine_size_id');
    }

    public function bodyType()
    {
        return $this->belongsTo('App\Models\VehicleBodyType', 'vehicle_body_type_id');
    }

    public function matchLogs()
    {
        return $this->hasMany('App\Models\VehicleMatchResults', 'vehicle_id');
    }
}
