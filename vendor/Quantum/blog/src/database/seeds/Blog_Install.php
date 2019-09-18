<?php
namespace Quantum\blog\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Settings;

class Blog_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Content')->first();

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Categories')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-sitemap',
                'url' => '/admin/categories',
                'title' => 'Categories',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 98
            ] );
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Tags')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-tag',
                'url' => '/admin/tags',
                'title' => 'Tags',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 99
            ] );
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Posts')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-list-alt',
                'url' => '/admin/posts',
                'title' => 'Posts',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 2)->where('title', 'Blog')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 2,
                'icon' => 'far fa-list-alt',
                'url' => '/admin/blog/settings',
                'title' => 'Posts',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );
        }
        
       $blog = \Quantum\base\Models\Categories::firstOrCreate([
            'name' => 'Blog', 
            'slug' => 'blog',
            'description' => 'Main Blog Category',
            'parent_id' => null,
            'icon' => 'far fa-list-alt',
            'user_id' => 0,
            'system' => 1,
           'tenant' => config('app.name')
        ]);

        \Quantum\base\Models\Categories::firstOrCreate([
            'name' => 'Uncategorised',
            'slug' => 'uncategorised',
            'parent_id' => $blog->id,
            'area' => 'blog',
            'description' => 'Blog Uncategorised',
            'icon' => '',
            'user_id' => 0,
            'system' => 1,
            'tenant' => config('app.name')
        ]);

        Settings::firstOrCreate( [
            'name' => 'blog_default_category' ,
            'data' => 'blog',
            'tenant' => config('app.name')
        ] );

        Settings::firstOrCreate( [
            'name' => 'default_uncategorised_category' ,
            'data' => 'uncategorised',
            'tenant' => config('app.name')
        ] );

        Settings::firstOrCreate([
            'name' => 'enable_blog',
            'data' => '1',
            'tenant' => config('app.name')
        ]);

        Settings::firstOrCreate([
        'name' => 'blog_link_structure',
        'data' => '1',
        'tenant' => config('app.name')
        ]);

        \Artisan::call('cache:clear');
    }
}
