<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCountLog extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'vehicle_count_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['total'];
}
