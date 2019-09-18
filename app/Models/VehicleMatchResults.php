<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMatchResults extends Model
{
    protected $table = 'vehicle_match_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vehicle_id', 'title', 'vehicle_make', 'vehicle_model'];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicles', 'vehicle_id');
    }

}
