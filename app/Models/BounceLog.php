<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BounceLog extends Model
{

    protected $fillable = ['email', 'type', 'details'];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bounce_log';


}
