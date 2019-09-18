<?php
namespace Quantum\base\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}