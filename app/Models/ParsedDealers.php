<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParsedDealers extends Model
{
    protected $table = 'parsedDealers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'processing', 'error'];


}
