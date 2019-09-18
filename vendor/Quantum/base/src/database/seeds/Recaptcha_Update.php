<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\NotificationEvents;
use Quantum\base\Models\Settings;

class Recaptcha_Update extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Settings')->first();
        if ( ! $menuItem = MenuItems::where('menu_id', $menu->id)->where('parent_id', $logDD->id)->where('title', 'Recaptcha')->first()) {
            MenuItems::create([
                'menu_id'    => $menu->id,
                'parent_id'  => $logDD->id,
                'icon'       => 'far fa-check-square',
                'url'        => '/admin/recaptcha/settings',
                'title'      => 'Recaptcha',
                'permission' => 'view-admin-area',
                'type'       => 'normal',
                'position'   => 7
            ]);
            \Artisan::call('cache:clear');

            Settings::create([
                'name' => 'recaptcha_site_key',
                'data' => ''
            ]);

            Settings::create([
                'name' => 'recaptcha_secret_key',
                'data' => ''
            ]);

            Settings::create([
                'name' => 'recaptcha_register',
                'data' => '0'
            ]);

            Settings::create([
                'name' => 'recaptcha_login',
                'data' => '0'
            ]);

            Settings::create([
                'name' => 'recaptcha_password',
                'data' => '0'
            ]);

            Settings::create([
                'name' => 'recaptcha_guest_ticket',
                'data' => '0'
            ]);

            Settings::create([
                'name' => 'recaptcha_guest_newsletter',
                'data' => '0'
            ]);

        }
    }
}
