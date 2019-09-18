<?php

namespace Quantum\base\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(
            'base::members.Page.show', 'Quantum\base\Http\ViewComposers\MembersPageComposer'
        );
        view()->composer(
            'base::members.*', 'Quantum\base\Http\ViewComposers\MembersComposer'
        );
        view()->composer(
            'base::errors.*', 'Quantum\base\Http\ViewComposers\MembersComposer'
        );
        view()->composer(
            'members.*', 'Quantum\base\Http\ViewComposers\MembersComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}