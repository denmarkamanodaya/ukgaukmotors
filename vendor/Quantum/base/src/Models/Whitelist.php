<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['ip_address', 'info'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'whitelist';
}
