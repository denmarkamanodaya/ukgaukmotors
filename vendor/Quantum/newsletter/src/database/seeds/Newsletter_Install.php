<?php
namespace Quantum\newsletter\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;

class Newsletter_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Newsletter')->first())
        {
            $menuItem = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'far fa-newspaper',
                'url' => '',
                'title' => 'Newsletter',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 12
            ] );
            \Artisan::call('cache:clear');
        }

        if(!$menuItem2 = MenuItems::where('menu_id',$menu->id)->where('parent_id', $menuItem->id)->where('title', 'Newsletters')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-newspaper',
                'url' => '/admin/newsletter',
                'title' => 'Newsletters',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-users',
                'url' => '/admin/newsletter/subscribers',
                'title' => 'Subscribers',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-envelope',
                'url' => '/admin/newsletter/mail',
                'title' => 'Send Mail',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 3
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-envelope-open',
                'url' => '/admin/newsletter/maillog',
                'title' => 'Mail Log',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 4
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-share',
                'url' => '/admin/newsletter/import',
                'title' => 'Import',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 5
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-code',
                'url' => '/admin/newsletter/templates',
                'title' => 'Templates',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 6
            ] );

            \Artisan::call('cache:clear');
        }

        \Quantum\newsletter\Models\NewsletterTemplates::firstOrCreate([
            'title' => 'Blank',
            'content' => '[mailcontent]',
            'template_type' => 'theme'
        ]);



    }
}
