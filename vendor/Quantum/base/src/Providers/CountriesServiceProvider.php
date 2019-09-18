<?php

namespace Quantum\base\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * CountryListServiceProvider
 *
 */ 

class CountriesServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
    * Bootstrap the application.
    *
    * @return void
    */
    public function boot()
    {
        
    }
        
    /**
     * Register everything.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('countries', function () {
            return $this->app->make('Quantum\base\Services\CountryService');
        });
    }

    
}