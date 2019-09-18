<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/

namespace Quantum\blog\Module;


class Module
{
    
    public function install()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\blog\database\seeds\Blog_Install'));
    }

    public function installMultiSite()
    {
        \Artisan::call('db:seed', array('--class' => '\Quantum\blog\database\seeds\Blog_Install'));
    }

    public function update()
    {
        
    }

}