<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealersFeatures extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'dealers_features';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'system', 'position', 'icon'];


    public function dealer()
    {
        return $this->belongsToMany(\App\Models\Dealers::class, 'dealer_features');
    }
}
