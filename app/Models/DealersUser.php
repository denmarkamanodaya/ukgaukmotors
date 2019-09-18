<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Quantum\calendar\Traits\CalEventable;

class DealersUser extends Model
{

    use CalEventable;
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'dealer_user';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dealers_id', 'user_id'];

    public $timestamps  = false;


    public function dealers()
    {
        return $this->belongsTo(\App\Models\Dealers::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
