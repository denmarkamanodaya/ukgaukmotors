<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Failures extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['ip_address', 'info', 'released'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'failures';

    /**
     * Specify extra date fields
     *
     * @var array
     */
    protected $dates = ['released'];
}
