<?php

namespace Quantum\base\Listeners;

use Quantum\base\Services\FirewallService;

class Firewall
{

    /**
     * @var FirewallService
     */
    private $fw;

    public function __construct(FirewallService $fw)
    {
        $this->fw = $fw;
    }

    /**
     * Handle user login events.
     */
    public function onLoginFailed($event) {
        $this->fw->init();
        $this->fw->failure('Login Failed Attempt');
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogin($event) {
        $this->fw->init();
        $this->fw->login();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Failed',
            'Quantum\base\Listeners\Firewall@onLoginFailed'
        );

        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Quantum\base\Listeners\Firewall@onUserLogin'
        );

    }

}