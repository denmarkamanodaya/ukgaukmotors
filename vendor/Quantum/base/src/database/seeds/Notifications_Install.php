<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Settings;
use Quantum\base\Models\NotificationEvents;

class Notifications_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        $menuSettings = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Settings')->first();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $menuSettings->id)->where('title', 'Notifications')->first()) {
            MenuItems::create([
                'menu_id'    => $menu->id,
                'parent_id'  => $menuSettings->id,
                'icon'       => 'far fa-bullhorn',
                'url'        => '/admin/notifications',
                'title'      => 'Notifications',
                'permission' => 'view-admin-area',
                'type'       => 'normal',
                'position'   => 6
            ]);
            \Artisan::call('cache:clear');

            Settings::create([
                'name' => 'site_notification_email',
                'data' => ''
            ]);

            Settings::create([
                'name' => 'site_notification_pushbullet_api',
                'data' => 'o.TifNJh88gKFWOuv0kaZoi3KbpWogYUCC'
            ]);

        }

        if(!$email = Emailing::where('title', 'Notification - User Login')->tenant()->first())
        {
            Emailing::create( [
                'title' => 'Notification - User Login' ,
                'subject' => 'User Login [username]' ,
                'content_html' => 'Hello,<br>
<br>
The following user has just logged in to the members area.<br>
Username: [username]<br>
Name : [firstname] [lastname]<br>
<br>
<br>
Best Wishes' ,
                'content_text' => 'Hello

The following user has just logged in to the members area.
Username: [username]
Name : [firstname] [lastname]

Login url
[loginurl]

Best Wishes
' ,
            ] ); 
        }

        if(!$email = Emailing::where('title', 'Notification - User Registered')->tenant()->first()) {
            Emailing::create([
                'system'       => '1',
                'title'        => 'Notification - User Registered',
                'subject'      => 'User Registered [username]',
                'content_html' => 'Hello,<br>
<br>
The following user has just registered.<br>
Username: [username]<br>
Name : [firstname] [lastname]<br>
<br>
<br>
Best Wishes',
                'content_text' => 'Hello

The following user has just registered.
Username: [username]
Name : [firstname] [lastname]

Best Wishes
',
            ]);
        }

        NotificationEvents::firstOrCreate([
            'event' => 'UserRegistered',
            'title' => 'User Registered',
            'description' => 'When a new member creates an account',
            'emails_title' => 'Notification - User Registered'
        ]);

        Emailing::where('title', 'User Login')->tenant()
            ->update(['title' => 'Notification - User Login']);

        NotificationEvents::where('emails_title', 'User Login')
            ->update(['emails_title' => 'Notification - User Login']);


        if(!$email = Emailing::where('title', 'Notification - User Upgraded')->tenant()->first()) {
            Emailing::create([
                'system'       => '1',
                'title'        => 'Notification - User Upgraded',
                'subject'      => 'User Upgraded [username]',
                'content_html' => 'Hello,<br>
<br>
The following user has just upgraded.<br>
Username: [username]<br>
<br>
Membership : [upgradeMembership]
<br>
Best Wishes',
                'content_text' => 'Hello

The following user has just registered.
Username: [username]
Membership : [upgradeMembership]

Best Wishes
',
            ]);
        }

        NotificationEvents::firstOrCreate([
            'event' => 'UserUpgraded',
            'title' => 'User Upgraded',
            'description' => 'When a member upgrades their account',
            'emails_title' => 'Notification - User Upgraded'
        ]);


        if(!$email = Emailing::where('title', 'Notification - Payment Received')->tenant()->first()) {
            Emailing::firstOrCreate([
                'system'       => '1',
                'tenant'       => config('app.name'),
                'title'        => 'Notification - Payment Received',
                'subject'      => 'Payment Received',
                'content_html' => 'Hello,<br>
<br>
The following transaction has just been received.<br>
Username: [username]<br>Note : If guest payment the user will be listed as the main admin
<br>
Amount : [payment-amount]

Payment For: [payment-items]
<br>
Best Wishes',
                'content_text' => 'Hello

The following transaction has just been received.
Username: [username]
Note : If guest payment the user will be listed as the main admin

Amount : [payment-amount]

Payment For: [payment-items]

Best Wishes
',
            ]);
        }

        NotificationEvents::firstOrCreate([
            'event' => 'PaymentReceived',
            'title' => 'Payment Received',
            'description' => 'When a payment is received',
            'emails_title' => 'Notification - Payment Received'
        ]);
        
        
    }
}
