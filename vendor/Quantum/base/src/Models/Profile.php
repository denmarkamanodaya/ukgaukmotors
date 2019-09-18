<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'address', 'address2', 'city', 'county', 'postcode', 'country_id', 'telephone', 'bio', 'picture'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User');
    }

    public function country()
    {
        return $this->belongsTo(\Quantum\base\Models\Countries::class);
    }
}
