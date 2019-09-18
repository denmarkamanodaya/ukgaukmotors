<?php

namespace Quantum\base\Providers;

use Illuminate\Support\ServiceProvider;

class UserShortcodeServiceProvider extends ServiceProvider {

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
        $this->app->singleton('usershortcode', function () {
            return $this->app->make('Quantum\base\Services\UserShortcodeService');
        });
    }
}