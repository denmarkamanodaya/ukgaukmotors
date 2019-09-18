<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ActivityLoggerServiceProvider.php
 **/

namespace Quantum\base\Providers;

use Illuminate\Support\ServiceProvider;

class ActivityLoggerServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('activitylogger', function () {
            return $this->app->make('Quantum\base\Services\ActivityLogService');
        });
    }
}