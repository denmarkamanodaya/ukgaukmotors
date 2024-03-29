<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('\App\Providers\HelperServiceProvider');
        view()->composer(
            'Theme.gaukmotors.members.*', 'Quantum\base\Http\ViewComposers\MembersComposer'
        );
        $this->app->register('\App\Providers\ComposerServiceProvider');
    }
}
