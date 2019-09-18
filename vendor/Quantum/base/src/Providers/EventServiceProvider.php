<?php

namespace Quantum\base\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'Quantum\base\Handlers\AuthLoginEventHandler',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Quantum\base\Listeners\Firewall',
        'Quantum\base\Listeners\ActivityListener',
        'Quantum\base\Listeners\NotificationListener',
        'Quantum\base\Listeners\MembershipListener',
    ];
}
