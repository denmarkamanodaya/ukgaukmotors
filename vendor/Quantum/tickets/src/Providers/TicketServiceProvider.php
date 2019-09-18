<?php

namespace Quantum\tickets\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class TicketServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('base::admin.Template', 'Quantum\tickets\Http\ViewComposers\AdminComposer');
        View::composer('base::members.Template', 'Quantum\tickets\Http\ViewComposers\AdminComposer');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../Http/routes.php';
        }

        //$this->loadViewsFrom(base_path('resources/views'), 'tickets');
        $this->loadViewsFrom(__DIR__.'/../views', 'tickets');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([__DIR__.'/../views' => base_path('resources/views')], 'views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'migrations');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //$this->commands($this->commands);
        $this->app->register('\Quantum\tickets\Providers\HelperServiceProvider');
    }
}