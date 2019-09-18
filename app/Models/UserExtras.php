<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExtras extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'user_extras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'item_name', 'amount', 'expires', 'expire_date'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
