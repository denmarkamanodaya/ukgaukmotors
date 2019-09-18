<?php
namespace Quantum\tickets\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;

class Tickets_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Tickets')->first())
        {
            $menuItem = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'far fa-ticket-alt',
                'url' => '',
                'title' => 'Tickets',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 12
            ] );
            \Artisan::call('cache:clear');
        }

        if(!$menuItem2 = MenuItems::where('menu_id',$menu->id)->where('parent_id', $menuItem->id)->where('title', 'View Tickets')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-ticket-alt',
                'url' => '/admin/tickets',
                'title' => 'View Tickets',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-sitemap',
                'url' => '/admin/ticket/departments',
                'title' => 'Departments',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-bolt',
                'url' => '/admin/ticket/statuses',
                'title' => 'Statuses',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 3
            ] );

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-cogs',
                'url' => '/admin/ticket/settings',
                'title' => 'Settings',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 4
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$status = \Quantum\tickets\Models\TicketStatus::where('name', 'Open')->first())
        {
            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'Open',
                'slug' => 'open',
                'description' => 'The ticket is open for replies',
                'icon' => 'far fa-ticket-alt',
                'system' => 1,
                'default' => 1,
                'colour' => '#000000'
            ]);

            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'Closed',
                'slug' => 'closed',
                'description' => 'The ticket has been closed',
                'icon' => 'far fa-ticket-alt',
                'system' => 1,
                'colour' => '#000000'
            ]);

            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'Awaiting Reply',
                'slug' => 'awaiting_reply',
                'description' => 'The ticket is waiting for a reply',
                'icon' => 'far fa-ticket-alt',
                'system' => 1,
                'colour' => '#000000'
            ]);

            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'User Replied',
                'slug' => 'user_replied',
                'description' => 'The ticket has been replied by the user.',
                'icon' => 'far fa-comment',
                'system' => 1,
                'default' => 0,
                'colour' => '#000000'
            ]);

            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'Staff Replied',
                'slug' => 'staff_replied',
                'description' => 'The ticket has been replied by a staff member.',
                'icon' => 'far fa-comment-alt',
                'system' => 1,
                'default' => 0,
                'colour' => '#000000'
            ]);
        }

        if(!$status = \Quantum\tickets\Models\TicketDepartment::where('name', 'General')->first())
        {
            \Quantum\tickets\Models\TicketDepartment::create([
                'name' => 'General Support',
                'slug' => 'general',
                'description' => 'General support department',
                'icon' => 'far fa-ticket-alt',
                'system' => 1,
                'colour' => '#000000',
                'default' => 1,
            ]);

        }

    }
}
