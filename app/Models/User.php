<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : User.php
 **/

namespace App\Models;


class User extends \Quantum\base\Models\User
{
    public function shortlist()
    {
        return $this->belongsToMany('App\Models\Vehicles', 'user_vehicle', 'user_id', 'vehicle_id');
    }

    public function garageFeed()
    {
        return $this->hasMany('App\Models\GarageFeed')->orderBy('position');
    }

    public function tours()
    {
        return $this->hasMany('App\Models\Tours');
    }
}