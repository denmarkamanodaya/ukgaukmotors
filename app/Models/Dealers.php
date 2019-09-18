<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Quantum\calendar\Traits\CalEventable;

class Dealers extends Model
{

    use CalEventable;
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'dealers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'logo', 'address', 'country_id', 'postcode', 'town', 'phone', 'email', 'website', 'auction_url',
        'online_bidding_url', 'details', 'buyers_premium', 'directions', 'rail_station', 'notes', 'longitude', 'latitude', 'type', 'status', 'county', 'has_streetview'];
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Models\DealersMedia');
    }

    public function vehiclesCount()
    {
        return $this->hasOne('App\Models\Vehicles', 'dealer_id')
            ->selectRaw('dealer_id, count(*) as aggregate')
            ->groupBy('dealer_id');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicles');
    }

    public function scopeSearchLocation($query, $location)
    {
        if ($location) $query->where('county', $location);
    }

    public function scopeSearchName($query, $name)
    {
        if ($name) $query->where('name', 'LIKE', '%'.$name.'%');
    }

    public function scopeSearchAuctioneer($query, $auctioneer)
    {
        if ($auctioneer) $query->where('slug', $auctioneer);
    }

    public function scopeSearchCategories($query, $categories)
    {
        if (is_array($categories)) $query->whereHas('categories', function($query) use($categories) {
            $query->whereIn('id', $categories);
        });
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Models\DealerCategories::class, 'dealers_categories');
    }

    public function features()
    {
        return $this->belongsToMany(\App\Models\DealersFeatures::class, 'dealer_features')->orderBy('name', 'ASC');
    }
}
