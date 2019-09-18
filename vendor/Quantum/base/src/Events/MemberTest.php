<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserUpgraded.php
 **/

namespace Quantum\base\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Event;
use Quantum\base\Models\User;

class MemberTest extends Event
{
    use SerializesModels;
    /**
     * @var User
     */
    public $user;


    /**
     * Create a new event instance.
     *
     * @param UserMembership $userMembership
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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