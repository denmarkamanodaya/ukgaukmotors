<?php

namespace Quantum\base\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $gate->before(function($user, $ability) {
            return $user->isSuperAdmin();
        });
        foreach($this->getPermissions() as $permission)
        {
            $gate->define($permission->name, function ($user) use ($permission) {
               //$user->hasRole($permission->roles);
                return $user->hasPermissionTo($permission);
            });
        }
    }

    protected function getPermissions()
    {
        return Cache::rememberForever('acl_permissions', function () {
        try {
            return \Quantum\base\Models\Permission::with('roles')->get();
        } catch (\Exception $e) {
        return [];
        }
        });

    }

    protected function forgetCachedPermissions()
    {
        Cache::forget('acl_permissions');
    }
}
