<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/

namespace Quantum\base\Module;


use Illuminate\Support\Facades\Artisan;

class Module
{
    public function install()
    {
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\ACLTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\UserTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\MenuTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\SettingsTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\HelpTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\EmailsTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\NewsSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\CountriesSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Commerce_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Activity_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Notifications_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Page_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Shortcode_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Membership_Install', '--force' => true));

    }

    public function installMultiSite()
    {
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\MenuTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\SettingsTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\EmailsTableSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\NewsSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Commerce_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Activity_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Notifications_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Page_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Shortcode_Install', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Membership_Install', '--force' => true));
    }

    public function update()
    {
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Activity_Update_Seeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Base_Update_1_Seeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Base_Update_2_Seeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Base_Update_3_Seeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Base_Update_4_Seeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Recaptcha_Update', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\PageSnippetSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Shortcodes_Update', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\NotificationsSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\Notification_Update', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\CommerceSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => '\Quantum\base\database\seeds\CommerceSeeder2', '--force' => true));
    }

    
}