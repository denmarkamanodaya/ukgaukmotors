<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;

class Activity_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Logs')->first();
        
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Activity')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-cubes',
                'url' => '/admin/activity',
                'title' => 'Activity',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 10
            ] );
            \Artisan::call('cache:clear');
        }
    }
}
