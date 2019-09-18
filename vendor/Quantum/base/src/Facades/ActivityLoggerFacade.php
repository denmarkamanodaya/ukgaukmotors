<?php

namespace Quantum\base\Facades;

use Illuminate\Support\Facades\Facade;

class ActivityLoggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'activitylogger';
    }
}