<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserUpgraded.php
 **/

namespace Quantum\base\Events;

use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Quantum\base\Models\UserMembership;

class UserUpgraded extends Event
{
    use SerializesModels;
    /**
     * @var UserMembership
     */
    public $userMembership;

    /**
     * Create a new event instance.
     *
     * @param UserMembership $userMembership
     */
    public function __construct( UserMembership $userMembership)
    {
        $this->userMembership = $userMembership;
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