<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarageFeed extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'garage_feed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'search', 'auctioneer', 'location', 'vehicleMake', 'vehicleModel', 'auctionDay', 'position', 'notify', 'vehicle_listing_type', 'parent_id'];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function auctioneerD()
    {
        return $this->belongsTo('App\Models\Dealers', 'auctioneer', 'slug');
    }

    public function vehicleModelD()
    {
        return $this->belongsTo('App\Models\VehicleModel', 'vehicleModel', 'id');
    }

    public function vehicleMakeD()
    {
        return $this->belongsTo('App\Models\VehicleMake', 'vehicleMake', 'slug');
    }
}
