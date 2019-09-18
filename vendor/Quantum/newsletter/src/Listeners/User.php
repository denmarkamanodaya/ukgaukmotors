<?php

namespace Quantum\newsletter\Listeners;

use Quantum\base\Services\FirewallService;
use Quantum\newsletter\Services\EventService;

class User
{

    /**
     * @var EventService
     */
    private $eventService;

    public function __construct(EventService $eventService)
    {

        $this->eventService = $eventService;
    }

    /**
     * Handle user ProfileUpdated events.
     */
    public function ProfileUpdated($event) {
        $this->eventService->ProfileUpdated($event);
    }

    public function UserCreated($event)
    {
        $this->eventService->UserCreated($event);
    }

    public function UserDeleted($event)
    {
        $this->eventService->UserDeleted($event);
    }

    public function GainedRole($event)
    {
        $this->eventService->RoleChange($event);
    }

    public function LostRole($event)
    {
        $this->eventService->RoleChange($event, 'lose');
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Quantum\base\Events\ProfileUpdated',
            'Quantum\newsletter\Listeners\User@ProfileUpdated'
        );

        $events->listen(
            'Quantum\membership\Events\UserRegistered',
            'Quantum\newsletter\Listeners\User@UserCreated'
        );

        $events->listen(
            'Quantum\base\Events\UserDeleted',
            'Quantum\newsletter\Listeners\User@UserDeleted'
        );

        $events->listen(
            'Quantum\base\Events\GainedRole',
            'Quantum\newsletter\Listeners\User@GainedRole'
        );

        $events->listen(
            'Quantum\base\Events\LostRole',
            'Quantum\newsletter\Listeners\User@LostRole'
        );

    }

}