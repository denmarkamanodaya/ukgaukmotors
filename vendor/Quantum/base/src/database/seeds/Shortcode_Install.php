<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Shortcodes;

class Shortcode_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Extra')->first())
        {
            $menuItem = MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => 0,
                'icon' => 'far fa-gears',
                'url' => '',
                'title' => 'Extra',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 11
            ] );
            \Artisan::call('cache:clear');
        }

        if(!$menuItem2 = MenuItems::where('menu_id',$menu->id)->where('parent_id', $menuItem->id)->where('title', 'Shortcodes')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $menuItem->id,
                'icon' => 'far fa-retweet',
                'url' => '/admin/shortcodes',
                'title' => 'Shortcodes',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );
            \Artisan::call('cache:clear');
        }

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'username',
            'callback' => 'Quantum\base\Shortcodes\User::username',
            'title' => 'Username',
            'description' => 'Display Members Username',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'email',
            'callback' => 'Quantum\base\Shortcodes\User::email',
            'title' => 'Email',
            'description' => 'Display Members Email',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'firstname',
            'callback' => 'Quantum\base\Shortcodes\User::firstname',
            'title' => 'Username',
            'description' => 'Display Members Firstname',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'lastname',
            'callback' => 'Quantum\base\Shortcodes\User::lastname',
            'title' => 'Lastname',
            'description' => 'Display Members Lastname',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'address',
            'callback' => 'Quantum\base\Shortcodes\User::address',
            'title' => 'Address',
            'description' => 'Display Members Address',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'address2',
            'callback' => 'Quantum\base\Shortcodes\User::address2',
            'title' => 'Address2',
            'description' => 'Display Members Address2',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'city',
            'callback' => 'Quantum\base\Shortcodes\User::city',
            'title' => 'City',
            'description' => 'Display Members City',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'county',
            'callback' => 'Quantum\base\Shortcodes\User::county',
            'title' => 'County',
            'description' => 'Display Members County',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'postcode',
            'callback' => 'Quantum\base\Shortcodes\User::postcode',
            'title' => 'Postcode',
            'description' => 'Display Members Postcode',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'country',
            'callback' => 'Quantum\base\Shortcodes\User::country',
            'title' => 'Country',
            'description' => 'Display Members Country',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'telephone',
            'callback' => 'Quantum\base\Shortcodes\User::telephone',
            'title' => 'Telephone',
            'description' => 'Display Members Telephone',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'bio',
            'callback' => 'Quantum\base\Shortcodes\User::bio',
            'title' => 'Bio',
            'description' => 'Display Members Bio',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'avatar',
            'callback' => 'Quantum\base\Shortcodes\User::avatar',
            'title' => 'Profile Picture',
            'description' => 'Display Members profile Picture',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Bootstrap',
            'name' => 'row',
            'callback' => 'Quantum\base\Shortcodes\Bootstrap::row',
            'title' => 'Row',
            'description' => 'Add row tags [row]Your Content[/row]',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Bootstrap',
            'name' => 'col',
            'callback' => 'Quantum\base\Shortcodes\Bootstrap::col',
            'title' => 'Column',
            'description' => 'Add column tags [col]Your Content[/col]<br>Options available is size: [col size=12]<br>Conforms to bootstrap grid size',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'role',
            'callback' => 'Quantum\base\Shortcodes\Role::role',
            'title' => 'Role',
            'description' => 'Show content base on user role [role name="member"]Your Content[/role]<br>Options available is role: [role name="admin"]<br>Multiple roles must be seperated with a comma [role name="member,bronze"]',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'news',
            'callback' => 'Quantum\base\Shortcodes\News::news',
            'title' => 'News',
            'description' => 'Show the news content [news].',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'register-form',
            'callback' => 'Quantum\base\Shortcodes\Forms::register',
            'title' => 'Registration Form',
            'description' => 'Show the registration form.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'contact-form',
            'callback' => 'Quantum\base\Shortcodes\Forms::contact',
            'title' => 'Contact Form',
            'description' => 'Show the contact form.',
        ]);

        \Shortcode::clearCache();
    }
}
