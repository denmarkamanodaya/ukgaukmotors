<?php

namespace Quantum\base\Handlers;

use Carbon\Carbon;

class AuthLoginEventHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Events  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->previous_login_date = $event->user->last_login;
        $event->user->last_login = Carbon::now()->toDateTimeString();
        $event->user->last_login_ip = \Request::ip();
        $event->user->save();
        return;
    }
}
