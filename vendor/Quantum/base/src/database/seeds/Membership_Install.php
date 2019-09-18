<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Settings;

class Membership_Install extends Seeder
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
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Membership')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-user-plus',
                'url' => '/admin/membership',
                'title' => 'Membership',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 3
            ] );
            \Artisan::call('cache:clear');

        }

        if(!$members_dashboard = Settings::where('name', 'register_first_name')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_first_name',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_last_name')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_last_name',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_address')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_address',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_address2')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_address2',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_city')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_city',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_county')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_county',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_postcode')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_postcode',
                'data' => '1'
            ]);
        }
        if(!$members_dashboard = Settings::where('name', 'register_country')->tenant()->first())
        {
            Settings::create([
                'name' => 'register_country',
                'data' => '1'
            ]);
        }

        \Cache::forget('site.settings');
    }
}
