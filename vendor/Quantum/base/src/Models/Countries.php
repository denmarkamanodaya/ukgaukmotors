<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'capital', 'citizenship', 'country_code', 'currency', 'currency_code', 'currency_sub_unit', 'full_name', 
        'iso_3166_2', 'iso_3166_3', 'name', 'region_code', 'sub_region_code', 'eea', 'calling_code', 'currency_symbol', 'flag'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';
}
