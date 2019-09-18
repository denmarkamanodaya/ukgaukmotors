<?php

namespace Quantum\base\Providers;

use Quantum\base\Models\Settings;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('settings', function () {
            return $this->app->make('Quantum\base\Services\Settings');
        });
    }
}
