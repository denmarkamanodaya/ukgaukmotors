<?php

namespace Quantum\base\Listeners;


use Quantum\base\Services\ActivityLogService;

class ActivityListener
{


    /**
     * @var ActivityLogService
     */
    private $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }


    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {

        $this->activityLogService->log('User Login : '.config('app.name'));

    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Quantum\base\Listeners\ActivityListener@onUserLogin'
        );

    }

}