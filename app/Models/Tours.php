<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'tour_seen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name'];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
