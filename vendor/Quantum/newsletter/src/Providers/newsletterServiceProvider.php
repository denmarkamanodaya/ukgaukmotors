<?php

namespace Quantum\newsletter\Providers;

use Illuminate\Support\ServiceProvider;

class newsletterServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Quantum\newsletter\Console\Commands\SendShots',
        'Quantum\newsletter\Console\Commands\SendResponders',
        'Quantum\newsletter\Console\Commands\CountOpened',
        'Quantum\newsletter\Console\Commands\ImportQueue'
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__.'/../views', 'newsletter');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../views' => base_path('resources/views/vendor/newsletter'),
        ]);

        $this->publishes([
            __DIR__.'/../config/newsletter.php' => config_path('newsletter.php'),
        ]);

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../public/assets' => public_path('assets'),
        ], 'public');

        $this->mergeConfigFrom(
            __DIR__.'/../config/newsletter.php', 'newsletter'
        );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->app->register('\Quantum\newsletter\Providers\HelperServiceProvider');
        $this->app->register('\Quantum\newsletter\Providers\EventServiceProvider');
    }
}