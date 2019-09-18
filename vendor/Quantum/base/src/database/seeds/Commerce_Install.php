<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Settings;
use Quantum\base\Models\PaymentGateway;
use Quantum\base\Models\Shortcodes;

class Commerce_Install extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();
        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Settings')->first();
        if(!$menuItem = MenuItems::where('menu_id', $menu->id)->where('parent_id', $logDD->id)->where('title', 'Commerce')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-money-bill-alt',
                'url' => '/admin/commerce/settings',
                'title' => 'Commerce',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 4
            ] );
            \Artisan::call('cache:clear');
        }

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'checkout',
            'callback' => 'Quantum\base\Shortcodes\Checkout::index',
            'title' => 'Checkout',
            'description' => 'Add the commerce checkout page content',
        ]);

        if(!$page = \Quantum\base\Models\Page::where('area', 'public')->where('route', 'checkout')->tenant()->first())
        {
            $page = \Quantum\base\Models\Page::create( [
                'title' => 'Checkout' ,
                'subtitle' => '',
                'content' => '[checkout]',
                'area' => 'public',
                'status' => 'published',
                'route' => 'checkout',
            ] );
            $pageMeta = \Quantum\base\Models\PageMeta::create([
                'page_id' => $page->id,
                'featured_image' => '',
                'description' => '',
                'type' => '',
                'keywords' => '',
                'robots' => 'noindex, nofollow'
            ]);
        }

        if(!$page = \Quantum\base\Models\Page::where('area', 'members')->where('route', 'checkout')->tenant()->first())
        {
            $page = \Quantum\base\Models\Page::create( [
                'title' => 'Checkout' ,
                'subtitle' => '',
                'content' => '[checkout]',
                'area' => 'members',
                'status' => 'published',
                'route' => 'checkout',
            ] );

            $pageMeta = \Quantum\base\Models\PageMeta::create([
                'page_id' => $page->id,
                'featured_image' => '',
                'description' => '',
                'type' => '',
                'keywords' => '',
                'robots' => 'noindex, nofollow'
            ]);
        }

        if(!$page = \Quantum\base\Models\Page::where('area', 'members')->where('route', 'upgrade')->tenant()->first())
        {
            $page = \Quantum\base\Models\Page::create( [
                'title' => 'Upgrade' ,
                'subtitle' => '',
                'content' => 'Add Your Upgrade Content',
                'area' => 'members',
                'status' => 'published',
                'route' => 'upgrade',
            ] );
            $pageMeta = \Quantum\base\Models\PageMeta::create([
                'page_id' => $page->id,
                'featured_image' => '',
                'description' => '',
                'type' => '',
                'keywords' => '',
                'robots' => 'noindex, nofollow'
            ]);
        }

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'cart-url',
            'callback' => 'Quantum\base\Shortcodes\Cart::index',
            'title' => 'Cart Url',
            'description' => 'Get the link to add an item to the users cart.<br>Options available is membership: <br>[cart-url membership="silver"].',
        ]);

        if(!$members_dashboard = Settings::where('name', 'PaypalRest_ClientId')->tenant()->first())
        {
            Settings::create([
                'name' => 'PaypalRest_ClientId',
                'data' => ''
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'PaypalRest_ClientSecret')->tenant()->first())
        {
            Settings::create([
                'name' => 'PaypalRest_ClientSecret',
                'data' => ''
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'PaypalRest_mode')->tenant()->first())
        {
            Settings::create([
                'name' => 'PaypalRest_mode',
                'data' => 'live'
            ]);
        }

        if(!$members_dashboard = Settings::where('name', 'PaypalRest_ipn_passthrough')->tenant()->first())
        {
            Settings::create([
                'name' => 'PaypalRest_ipn_passthrough',
                'data' => ''
            ]);
        }

        \Cache::forget('site.settings');

        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Logs')->first();

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Payments')->first())
        {
            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-money-bill-alt',
                'url' => '/admin/commerce/logs',
                'title' => 'Payments',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );
            \Artisan::call('cache:clear');

        }

        if(!\Schema::hasColumn('transactions', 'user_id'))
        {
            \Schema::table('transactions', function ($table) {
                $table->integer('user_id')->unsigned()->index();
            });
        }

        if(!\Schema::hasColumn('payment_gateway', 'userTitle'))
        {
            \Schema::table('payment_gateway', function ($table) {
                $table->string('userTitle');
            });

            PaymentGateway::where('slug', 'PaypalRest')->update(['userTitle' => 'Paypal']);
        }
        
        
    }
}
