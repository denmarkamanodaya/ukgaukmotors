<?php

namespace Quantum\base\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Quantum\base\Models\Shortcodes;
use Quantum\base\Services\CartService;
use Quantum\base\Repositories\Payment;

class baseServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Quantum\base\Console\Commands\Module',
        'Quantum\base\Console\Commands\Firewall',
        'Quantum\base\Console\Commands\ChangeUrl',
        'Quantum\base\Console\Commands\MenuIcons',
        'Quantum\base\Console\Commands\CleanUp',
        'Quantum\base\Console\Commands\Expired',
        'Quantum\base\Console\Commands\CleanTags'
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {

        View::composer('members.Template', 'Quantum\base\Http\ViewComposers\MembersComposer');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__.'/../views', 'base');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../views' => base_path('resources/views'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../Config' => base_path('config'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../Config/firewall.php', 'firewall'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/main.php', 'main'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/modelDatabase.php', 'modelDatabase'
        );

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../public' => public_path(),
        ], 'public');

        Shortcodes::saved(function ($shortcode) {
            \Cache::forget('shortcodes');
            \Cache::forget('shortModal');
        });

        \Shortcode::registerAll();

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->app->register('\Quantum\base\Providers\EventServiceProvider');
        $this->app->register('\Quantum\base\Providers\AuthServiceProvider');
        $this->app->register('\Quantum\base\Providers\HelperServiceProvider');
        $this->app->register('\Quantum\base\Providers\MenuServiceProvider');
        $this->app->register('\Quantum\base\Providers\SettingsServiceProvider');
        $this->app->register('\Quantum\base\Providers\ValidatorServiceProvider');
        $this->app->register('\Quantum\base\Providers\ComposerServiceProvider');
        $this->app->register('\Quantum\base\Providers\UserShortcodeServiceProvider');
        $this->app->register('\Quantum\base\Providers\ThemeServiceProvider');
        $this->app->register('\Quantum\base\Providers\CountriesServiceProvider');
        $this->app->register('\Quantum\base\Providers\ExceptionServiceProvider');
        $this->app->register('\Quantum\base\Providers\ActivityLoggerServiceProvider');
        $this->app->singleton('shortcode', function () {
            return $this->app->make('Quantum\base\Services\ShortcodeService');
        });

        $session = $this->app['session'];
        $events = $this->app['events'];
        $this->app->singleton('cart', function() use($events, $session)
        {
            return new CartService($session, $events);
        });


        $this->app->singleton('payment', function()
        {
            return new Payment();
        });

        \Shortcode::register('checkout', 'Quantum\base\Shortcodes\Checkout::index');
    }
}