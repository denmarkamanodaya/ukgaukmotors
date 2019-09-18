<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ExceptionServiceProvider.php
 **/

namespace Quantum\base\Providers;

use Illuminate\Support\ServiceProvider;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/exception.php', 'exception'
        );
    }
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('exceptionLog', function () {
            return $this->app->make('Quantum\base\Services\ExceptionService');
        });
    }
}