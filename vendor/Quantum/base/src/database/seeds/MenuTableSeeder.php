<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder {
    public function run() {
        //Menu::truncate();
        $Menu = \Quantum\base\Models\Menu::create( [
            'title' => 'Main Admin Menu' ,
            'description' => 'Top Admin Menu' ,
            'role_id' => '2',
            'parent_start' => '<ul class="nav navbar-nav">',
            'parent_end' => '</ul>',
            'system' => 1
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'icon' => 'icon-display4',
            'url' => '/admin/dashboard',
            'title' => 'Dashboard',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 1
        ] );

        $menu_parent = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'icon' => 'far fa-cog',
            'url' => '',
            'title' => 'Settings',
            'permission' => 'view-admin-area',
            'type' => 'dropdown',
            'position' => 2
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => '',
            'url' => '',
            'title' => 'Site Settings & Tools',
            'permission' => 'view-admin-area',
            'type' => 'dropdown-header',
            'position' => 1
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-cog',
            'url' => '/admin/settings',
            'title' => 'General Settings',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 2
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-envelope',
            'url' => '/admin/emails',
            'title' => 'System Emails',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 2
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-ban',
            'url' => '/admin/acl',
            'title' => 'Acl',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 3
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-bars',
            'url' => '/admin/menu',
            'title' => 'Menu',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 4
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-info',
            'url' => '/admin/about',
            'title' => 'About',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 99
        ] );

        //firewall
        $menu_parent_firewall = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent->id,
            'icon' => 'far fa-lock',
            'url' => '',
            'title' => 'Firewall',
            'permission' => 'view-admin-area',
            'type' => 'dropdown-submenu',
            'position' => 5
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent_firewall->id,
            'icon' => 'far fa-bars',
            'url' => '/admin/firewall/failure',
            'title' => 'List Failing IP\'s',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 1
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent_firewall->id,
            'icon' => 'far fa-bars',
            'url' => '/admin/firewall/blocked',
            'title' => 'List Blocked IP\'s',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 2
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'parent_id' => $menu_parent_firewall->id,
            'icon' => 'far fa-bars',
            'url' => '/admin/firewall/whitelisted',
            'title' => 'List Whitelisted IP\'s',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 3
        ] );

        //users
        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'icon' => 'far fa-users',
            'url' => '/admin/users',
            'title' => 'Users',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 3
        ] );

        //Pages
        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'icon' => 'far fa-file-alt',
            'url' => '/admin/pages',
            'title' => 'Pages',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 4
        ] );

        //News
        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'icon' => 'far fa-newspaper-o',
            'url' => '/admin/news',
            'title' => 'News',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 5
        ] );

        //Emailer
        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id ,
            'icon' => 'far fa-envelope',
            'url' => '/admin/emailer',
            'title' => 'Emailer',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 9
        ] );

        //Members Menu
        $MenuMem = \Quantum\base\Models\Menu::create( [
            'title' => 'Main Members Menu' ,
            'description' => 'Top members menu' ,
            'role_id' => '3',
            'parent_start' => '<ul class="nav navbar-nav">',
            'parent_end' => '</ul>',
            'system' => 1
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $MenuMem->id ,
            'icon' => 'icon-display4',
            'url' => '/members/dashboard',
            'title' => 'Dashboard',
            'permission' => 'view-members-area',
            'type' => 'normal',
            'position' => 1
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $MenuMem->id ,
            'icon' => 'icon-display4',
            'url' => '/admin/dashboard',
            'title' => 'Admin Dashboard',
            'permission' => 'view-admin-area',
            'type' => 'normal',
            'position' => 2
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $MenuMem->id ,
            'icon' => 'far fa-user',
            'url' => '/members/profile',
            'title' => 'Your Profile',
            'permission' => 'view-members-area',
            'type' => 'normal',
            'position' => 3
        ] );

        //Public Menu
        $MenuPub = \Quantum\base\Models\Menu::create( [
            'title' => 'Main Public Menu' ,
            'description' => 'Top public menu' ,
            'role_id' => '4',
            'parent_start' => '<ul class="nav navbar-nav">',
            'parent_end' => '</ul>',
            'system' => 1
        ] );

        $menu_item = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $MenuPub->id ,
            'icon' => 'icon-display4',
            'url' => '/',
            'title' => 'Home',
            'permission' => 'view-public-area',
            'type' => 'normal',
            'position' => 1
        ] );

        $menu_parent = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id,
            'parent_id' => 0,
            'icon' => 'far fa-th-large',
            'url' => '',
            'title' => 'Logs',
            'permission' => 'view-admin-area',
            'type' => 'dropdown',
            'position' => 10
        ] );

        $menu_parent = \Quantum\base\Models\MenuItems::create( [
            'menu_id' => $Menu->id,
            'parent_id' => 0,
            'icon' => 'far fa-clipboard',
            'url' => '',
            'title' => 'Content',
            'permission' => 'view-admin-area',
            'type' => 'dropdown',
            'position' => 4
        ] );

    }
}
