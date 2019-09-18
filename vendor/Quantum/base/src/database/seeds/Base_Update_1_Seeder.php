<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Role;
use Quantum\base\Models\Settings;

class Base_Update_1_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$contact_thankyou_page = Settings::where('name', 'contact_thankyou_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'contact_thankyou_page',
                'data' => 'message-received'
            ]);
            \Cache::forget('site.settings');

        }

        if(!$email_theme = Settings::where('name', 'email_theme')->tenant()->first())
        {
            Settings::create([
                'name' => 'email_theme',
                'data' => '4'
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'members_home_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'members_home_page',
                'data' => 'members/dashboard'
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'members_home_page_title')->tenant()->first())
        {
            Settings::create([
                'name' => 'members_home_page_title',
                'data' => 'Dashboard'
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'site_country')->tenant()->first())
        {
            Settings::create([
                'name' => 'site_country',
                'data' => 'GBR'
            ]);
        }

        if(!Schema::hasColumn('emails', 'system'))
        {
            Schema::table('emails', function ($table) {
                $table->boolean('system')->default(1)->nullable();
            });
        }

        if(!$members_dashboard = Settings::where('name', 'members_checkout_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'members_checkout_page',
                'data' => 'members/checkout'
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'public_checkout_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'public_checkout_page',
                'data' => 'checkout'
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'members_upgrade_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'members_upgrade_page',
                'data' => 'upgrade'
            ]);
        }

        \Cache::forget('site.settings');

        if(!Schema::hasColumn('roles', 'system'))
        {
            Schema::table('roles', function ($table) {
                $table->boolean('system')->default(0)->nullable();
            });

            Role::whereIn('name', ['super_admin','admin','member','guest'])
                ->update(['system' => 1]);
        }

        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        if(!$menu_parent = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Logs')->first())
        {

            $menu_parent = \Quantum\base\Models\MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'far fa-th-large',
                'url' => '',
                'title' => 'Logs',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 10
            ] );

            MenuItems::where('menu_id', $menu->id)
                ->where('url','/admin/activity')
                ->update(['parent_id' => $menu_parent->id,'position' => 1]);

            \Artisan::call('cache:clear');
        }

        MenuItems::firstOrCreate( [
            'menu_id' => $menu->id,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-th',
            'url' => '/admin/logs',
            'title' => 'System Logs',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 2,
        ] );

        if(!$menu_parent = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Content')->first())
        {

            $menu_parent = \Quantum\base\Models\MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'far fa-clipboard',
                'url' => '',
                'title' => 'Content',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 4
            ] );


        }

        MenuItems::where('menu_id', $menu->id)
            ->where('url','/admin/pages')
            ->update(['parent_id' => $menu_parent->id,'position' => 1]);

        MenuItems::where('menu_id', $menu->id)
            ->where('url','/admin/news')
            ->update(['parent_id' => $menu_parent->id,'position' => 1]);

        \Artisan::call('cache:clear');
        
    }
}
