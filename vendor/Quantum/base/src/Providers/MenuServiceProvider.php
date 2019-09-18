<?php namespace Quantum\base\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('menu', function () {
            return $this->app->make('Quantum\base\Services\MenuService');
        });
    }

}