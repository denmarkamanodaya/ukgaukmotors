<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/

namespace Quantum\calendar\Module;


use Illuminate\Support\Facades\Artisan;

class Module
{
    
    public function install()
    {
        Artisan::call('db:seed', array('--class' => \Quantum\calendar\database\seeds\Calendar_Install::class, '--force' => true));
    }

    public function installMultiSite()
    {
        Artisan::call('db:seed', array('--class' => \Quantum\calendar\database\seeds\Calendar_Install::class, '--force' => true));
    }

    public function update()
    {
        //Artisan::call('db:seed', array('--class' => 'Calendar_Update_Seeder', '--force' => true));
    }

}