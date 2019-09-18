<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : SettingsFacade.php
 **/

namespace Quantum\base\Facades;

use Illuminate\Support\Facades\Facade;

class SettingsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}