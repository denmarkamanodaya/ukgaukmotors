<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : GainedRole.php
 **/

namespace Quantum\base\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Event;

class GainedRole extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $role;


    /**
     * Create a new event instance.
     *
     * @param $user
     */
    public function __construct($user, $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}