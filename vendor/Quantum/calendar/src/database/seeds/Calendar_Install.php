<?php
namespace Quantum\calendar\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;

class Calendar_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Extra')->first();
        
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Calendar')->first())
        {

            $menu_parent_calendar = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-calendar-alt',
                'url' => '',
                'title' => 'Calendar',
                'permission' => 'view-admin-area',
                'type' => 'dropdown-submenu',
                'position' => 10
            ] );

            $menu_item = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menu_parent_calendar->id,
                'icon' => 'far fa-calendar',
                'url' => '/admin/calendar',
                'title' => 'Calendar',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );

            $menu_item = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menu_parent_calendar->id,
                'icon' => 'far fa-clock',
                'url' => '/admin/calendar/events',
                'title' => 'Events',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            $menu_item = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menu_parent_calendar->id,
                'icon' => 'far fa-calendar-plus',
                'url' => '/admin/calendar/event/create',
                'title' => 'Create Site Event',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 3
            ] );

            \Artisan::call('cache:clear');
        }
    }
}
