<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserUpgraded.php
 **/

namespace Quantum\tickets\Events;

use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Quantum\membership\Models\UserMembership;
use Quantum\tickets\Models\Tickets;

class TicketReplied extends Event
{
    use SerializesModels;

    public $user;
    public $ticket;

    /**
     * Create a new event instance.
     *
     * @param
     */
    public function __construct(Tickets $ticket)
    {
        $this->user = $ticket->user;
        $this->ticket = $ticket;
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