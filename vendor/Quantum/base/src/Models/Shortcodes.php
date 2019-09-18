<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Shortcodes extends Model
{
    /**
    * The attributes that are fillable via mass assignment.
    *
    * @var array
    */
        protected $fillable = ['name', 'callback', 'params', 'type', 'title', 'description', 'system', 'hidden', 'area'];
    
        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'shortcodes';
}
