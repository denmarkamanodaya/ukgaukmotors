<?php

namespace Quantum\calendar\Providers;

use Illuminate\Support\ServiceProvider;

class calendarServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Quantum\calendar\Console\Commands\CalendarDaily'
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

        //$this->loadViewsFrom(base_path('resources/views'), 'activity');
        $this->loadViewsFrom(__DIR__.'/../views', 'calendar');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([__DIR__.'/../views' => base_path('resources/views')], 'views');

        $this->publishes([
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'migrations');

        $this->mergeConfigFrom(
            __DIR__.'/../Config/calendar.php', 'calendar'
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
        $this->app->register('\Quantum\calendar\Providers\HelperServiceProvider');
    }
}