<?php
namespace Quantum\base\Facades;

use Illuminate\Support\Facades\Facade;

class ThemeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'theme';
    }
}