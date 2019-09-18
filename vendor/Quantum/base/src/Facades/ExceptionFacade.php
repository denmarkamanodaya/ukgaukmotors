<?php
namespace Quantum\base\Facades;


use Illuminate\Support\Facades\Facade;

class ExceptionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'exceptionLog';
    }
}