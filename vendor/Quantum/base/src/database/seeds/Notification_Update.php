<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;

use Quantum\base\Models\Settings;

class Notification_Update extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$notif = \Quantum\base\Models\NotificationTypes::where('name', 'Pushover')->first())
        {
            \Quantum\base\Models\NotificationTypes::create( [
                'name' => 'Pushover',
                'slug' => 'pushover',
                'allow_members' => 1,
                'description' => 'User Key can be found in your Pushover members area. Accounts can be created <a href="https://pushover.net/" target="_blank">HERE</a>.',
            ] );
        }

        if(!$email_theme = Settings::where('name', 'site_notification_pushover_token')->tenant()->first())
        {
            Settings::create([
                'name' => 'site_notification_pushover_token',
                'data' => ''
            ]);
            \Artisan::call('cache:clear');
        }

        if(!$email_theme = Settings::where('name', 'site_notification_pushover_user')->tenant()->first())
        {
            Settings::create([
                'name' => 'site_notification_pushover_user',
                'data' => ''
            ]);
            \Artisan::call('cache:clear');
        }

    }
}
