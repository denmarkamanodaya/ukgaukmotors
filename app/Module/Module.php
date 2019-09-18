<?php

namespace App\Module;

use Illuminate\Support\Facades\Artisan;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Settings;
use Quantum\base\Models\Shortcodes;

/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/
class Module
{
    public function install()
    {
        //dd('app install');
    }

    public function update()
    {
        $this->update_1();
        Artisan::call('db:seed', array('--class' => 'MyGarageSeeder', '--force' => true));
        \Artisan::call('db:seed', array('--class' => 'Vehicle_Body_Type_Seeder', '--force' => true));
    }
    
    private function update_1()
    {
        if(!$contact_thankyou_page = Settings::where('name', 'gauk_import_api_key')->tenant()->first())
        {
            Settings::create([
                'name' => 'gauk_import_api_key',
                'data' => ''
            ]);

            Settings::create([
                'name' => 'gauk_import_api_status',
                'data' => 'live'
            ]);
            \Cache::forget('site.settings');

        }

        if(!$contact_thankyou_page = Settings::where('name', 'google_map_api_key')->tenant()->first())
        {
            Settings::create([
                'name' => 'google_map_api_key',
                'data' => ''
            ]);
            \Cache::forget('site.settings');

        }

        if(!$main_content_role = Settings::where('name', 'main_content_role')->tenant()->first())
        {
            Settings::create([
                'name' => 'main_content_role',
                'data' => ''
            ]);
            \Cache::forget('site.settings');

        }
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Vehicle Meta')->first())
        {

            $menu_parent = \Quantum\base\Models\MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'fas fa-truck',
                'url' => '',
                'title' => 'Vehicle Meta',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 12
            ] );
            

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menu_parent->id,
                'icon' => 'fas fa-bus',
                'url' => url('admin/vehicle-type'),
                'title' => 'Vehicle Types',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menu_parent->id,
                'icon' => 'fas fa-maxcdn',
                'url' => url('admin/vehicle-makes'),
                'title' => 'Vehicle Makes',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menu_parent->id,
                'icon' => 'fas fa-car',
                'url' => url('admin/vehicle-models'),
                'title' => 'Vehicle Models',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Auctioneers')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'fas fa-gavel',
                'url' => url('admin/auctioneers'),
                'title' => 'Auctioneers',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 13
            ] );
            
            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Vehicles')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'fas fa-car',
                'url' => url('admin/vehicles'),
                'title' => 'Vehicles',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 14
            ] );

            \Artisan::call('cache:clear');
        }
        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Settings')->first();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Gauk Settings')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'fas fa-cogs',
                'url' => url('admin/gauk-settings'),
                'title' => 'Gauk Settings',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            \Artisan::call('cache:clear');
        }

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'vehicle-search',
            'callback' => 'App\Shortcodes\VehicleSearch::searchWidget',
            'title' => 'Vehicle Search',
            'description' => 'Show the vehicle search widget [vehicle-search].<br>Options available is type: dashboard or normal : [vehicle-search type="dashboard"]<br>Defaults to normal with no type set.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'fullwidth',
            'callback' => 'App\Shortcodes\GaukTheme::fullWidth',
            'title' => 'Full Width',
            'description' => 'Use this to display content the full width of the page [fullwidth]YOUR CONTENT[/fullwidth].',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'vehicleMakeList',
            'callback' => 'App\Shortcodes\VehicleSearch::vehicleMakeList',
            'title' => 'Vehicle Make List',
            'description' => 'Show the vehicle make widget [vehicleMakeList].<br>Options available is rows : [vehicleMakeList rows="3"]<br>Defaults to 3 with no rows set.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'vehicleEndingSoon',
            'callback' => 'App\Shortcodes\VehicleSearch::vehicleEndingSoon',
            'title' => 'Vehicles - Ending Soon',
            'description' => 'Show the vehicles that are ending soon [vehicleEndingSoon].<br>Options available is amount: [vehicleEndingSoon amount="4"]<br>Max returned amount is 20.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'vehicleEndingSoonWidget',
            'callback' => 'App\Shortcodes\VehicleSearch::vehicleEndingSoonWidget',
            'title' => 'Vehicles - Ending Soon Widget',
            'description' => 'Show the vehicles that are ending soon widget [vehicleEndingSoonWidget].<br>Options available is amount: [vehicleEndingSoonWidget amount="4"]<br>Max returned amount is 20.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'vehicleLatest',
            'callback' => 'App\Shortcodes\VehicleSearch::vehicleLatest',
            'title' => 'Vehicles - Latest',
            'description' => 'Show the latest vehicles [vehicleLatest].<br>Options available is amount: [vehicleLatest amount="4"]<br>Max returned amount is 20.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'vehicleLatestWidget',
            'callback' => 'App\Shortcodes\VehicleSearch::vehicleLatestWidget',
            'title' => 'Vehicles - Latest Widget',
            'description' => 'Show the latest vehicles Widget [vehicleLatestWidget].<br>Options available is amount: [vehicleLatestWidget amount="4"]<br>Max returned amount is 20.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'facebookSale',
            'callback' => 'App\Shortcodes\Facebook::facebookSale',
            'title' => 'Vehicles - Latest Widget',
            'description' => 'Include the facebook sale javascript [facebookSale].<br>',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'currentBooks',
            'callback' => 'App\Shortcodes\Reading::currentBooks',
            'title' => 'Books - Currently Reading',
            'description' => 'Show which books they are reading [currentBooks].<br>',
        ]);

        \Shortcode::clearCache();

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Cache Settings')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-copy',
                'url' => url('admin/cache/settings'),
                'title' => 'Cache Settings',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 10
            ] );

            \Artisan::call('cache:clear');
        }

        if ($extraMenu = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Extra')->first())
        {
            if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $extraMenu->id)->where('title', 'Converter')->first())
            {

                MenuItems::create( [
                    'menu_id' => $menu->id,
                    'parent_id' => $extraMenu->id,
                    'icon' => 'far fa-exchange',
                    'url' => url('admin/convert'),
                    'title' => 'Converter',
                    'permission' => 'view-admin-area',
                    'type' => 'normal',
                    'position' => 10
                ] );

                \Artisan::call('cache:clear');
            }
        }

        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Content')->first();

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Books')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-book',
                'url' => url('admin/books'),
                'title' => 'Books',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 4
            ] );
            \Artisan::call('cache:clear');
        }

        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Vehicle Meta')->first();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Vehicle Features')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-map-signs',
                'url' => url('admin/vehicle-features'),
                'title' => 'Vehicle Features',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 4
            ] );
            \Artisan::call('cache:clear');
        }

        if($vehicles = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Vehicles')->where('type', '!=', 'dropdown')->first())
        {
            $vehicles->type = 'dropdown';
            $vehicles->url = '';
            $vehicles->save();
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $vehicles->id,
                'icon' => 'faa fa-gavel',
                'url' => url('admin/vehicles/auctions'),
                'title' => 'Auctions',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $vehicles->id,
                'icon' => 'far fa-newspaper',
                'url' => url('admin/vehicles/classifieds'),
                'title' => 'Classifieds',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $vehicles->id,
                'icon' => 'far fa-address-card',
                'url' => url('admin/vehicles/trade'),
                'title' => 'Trade',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 3
            ] );

            \Artisan::call('cache:clear');
        }

        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Vehicle Meta')->first();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Unclassified Vehicles')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-question',
                'url' => url('admin/vehicleTools/unclassified'),
                'title' => 'Unclassified Vehicles',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 10
            ] );
            \Artisan::call('cache:clear');
        }


    }
}