<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Notif.php
 **/

namespace Quantum\base\Http\Controllers\Members;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Quantum\base\Events\MemberTest;

class Notif extends Controller
{

    public function add()
    {
        $user = \Auth::user();
        $user->load(['notifications']);

        foreach ($user->notifications as $notification)
        {
            $notification->event()->detach(5);
            $notification->event()->attach(5);
        }
    }
    
    public function fire()
    {
        $user = \Auth::user();
        \Event::fire(new MemberTest($user));
    }

}