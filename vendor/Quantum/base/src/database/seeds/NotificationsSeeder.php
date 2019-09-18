<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(!$notification = \Quantum\base\Models\NotificationTypes::where('name', 'Email')->first())
        {
            \Quantum\base\Models\NotificationTypes::create( [
                'name' => 'Email',
                'slug' => 'email',
            ] );

            \Quantum\base\Models\NotificationTypes::create( [
                'name' => 'Pushbullet',
                'slug' => 'pushbullet',
            ] );

            \Quantum\base\Models\NotificationEvents::create( [
                'event' => 'Illuminate\Auth\Events\Login',
                'title' => 'User Login',
                'description' => 'When a user logs in to the members area.',
                'emails_title' => 'Notification - User Login'
            ] );
        }


    }
}
