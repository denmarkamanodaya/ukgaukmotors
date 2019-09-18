<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/

namespace Quantum\newsletter\Module;

use Illuminate\Support\Facades\Artisan;

class Module
{
    
    public function install()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\newsletter\database\seeds\Newsletter_Install'));
    }

    public function installMultiSite()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\newsletter\database\seeds\Newsletter_Install'));
    }

    public function update()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\newsletter\database\seeds\Newsletter_Update', '--force' => true));
    }
}