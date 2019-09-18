<?php

namespace Quantum\base\Facades;

use Illuminate\Support\Facades\Facade;

class UserShortcodeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'usershortcode';
    }
}