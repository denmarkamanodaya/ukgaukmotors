<?php

namespace Quantum\blog\Providers;

use Illuminate\Support\ServiceProvider;

class blogServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Quantum\blog\Console\Commands\Posts'
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

        //$this->loadViewsFrom(base_path('resources/views'), 'blog');
        $this->loadViewsFrom(__DIR__.'/../views', 'blog');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([__DIR__.'/../views' => base_path('resources/views')], 'views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../public/assets' => public_path('assets'),
        ], 'public');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->app->register('\Quantum\blog\Providers\HelperServiceProvider');
    }
}