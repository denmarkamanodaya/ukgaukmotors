<?php
namespace Quantum\newsletter\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;

class Newsletter_Update extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        if($menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Newsletter')->first())
        {
            if(!$menuItem2 = MenuItems::where('menu_id',$menu->id)->where('parent_id', $menuItem->id)->where('title', 'Themes')->first())
            {

                MenuItems::create( [
                    'menu_id' => $menu->id,
                    'parent_id' => $menuItem->id,
                    'icon' => 'far fa-object-group',
                    'url' => '/admin/newsletter/themes',
                    'title' => 'Themes',
                    'permission' => 'view-admin-area',
                    'type' => 'normal',
                    'position' => 7
                ] );

                \Artisan::call('cache:clear');
            }
        }


    }
}
