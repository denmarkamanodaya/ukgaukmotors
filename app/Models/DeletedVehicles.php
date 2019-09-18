<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class DeletedVehicles extends Model
{

    protected $fillable = ['vehicle_id'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'deleted_vehicles';


}
