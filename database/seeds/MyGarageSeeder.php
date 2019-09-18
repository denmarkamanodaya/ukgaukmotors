<?php

use Illuminate\Database\Seeder;

class MyGarageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(!$menu = \Quantum\base\Models\Menu::where('title', 'My Garage')->first())
        {
            $role = \Quantum\base\Models\Role::where('name', 'premium')->first();
            $Menu = \Quantum\base\Models\Menu::create( [
                'title' => 'My Garage' ,
                'description' => 'Side Menu' ,
                'role_id' => $role->id,
                'parent_start' => '<ul class="garageSideMenu">',
                'parent_end' => '</ul>',
                'system' => 1
            ] );

            $menu_item = \Quantum\base\Models\MenuItems::create( [
                'menu_id' => $Menu->id ,
                'parent_id' => '',
                'icon' => 'far fa-heart',
                'url' => url('members/mygarage/shortlist'),
                'title' => 'My Shortlist',
                'permission' => 'premium-access',
                'type' => 'normal',
                'position' => 1
            ] );

            $menu_item = \Quantum\base\Models\MenuItems::create( [
                'menu_id' => $Menu->id ,
                'parent_id' => '',
                'icon' => 'fas fa-car',
                'url' => url('members/mygarage/feed'),
                'title' => 'My Car Feed',
                'permission' => 'premium-access',
                'type' => 'normal',
                'position' => 1
            ] );

            $menu_item = \Quantum\base\Models\MenuItems::create( [
                'menu_id' => $Menu->id ,
                'parent_id' => '',
                'icon' => 'far fa-calendar',
                'url' => url('members/mygarage/calendar'),
                'title' => 'My Calendar',
                'permission' => 'premium-access',
                'type' => 'normal',
                'position' => 1
            ] );
        }


    }
}
