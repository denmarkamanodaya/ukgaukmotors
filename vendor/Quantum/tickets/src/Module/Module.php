<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/

namespace Quantum\tickets\Module;

use Illuminate\Support\Facades\Artisan;

class Module
{
    
    public function install()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\tickets\database\seeds\Tickets_Install'));
    }

    public function installMultiSite()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\tickets\database\seeds\Tickets_Install'));
    }

    public function update()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\tickets\database\seeds\Tickets_Update', '--force' => true));
        \Artisan::call('db:seed', array('--class' => '\Quantum\tickets\database\seeds\Tickets_Update_Public', '--force' => true));
    }
}