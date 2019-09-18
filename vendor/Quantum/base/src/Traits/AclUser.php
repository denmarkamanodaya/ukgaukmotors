<?php

namespace Quantum\base\Traits;

use Quantum\base\Models\Permission;
use Quantum\base\Models\Role;

trait AclUser {

    public function role()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class);
    }

    public function permission()
    {
        return $this->belongsToMany(\Quantum\base\Models\Permission::class);
    }

    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->role->contains('name', $roles);
        }
        if ($roles instanceof Role) {
            return $this->role->contains('id', $roles->id);
        }
        return (bool) !!$roles->intersect($this->role)->count();
    }

    public function hasPermission($permissions)
    {
        if (is_string($permissions)) {
            return $this->permission->contains('name', $permissions);
        }
        if ($permissions instanceof Permission) {
            return $this->permission->contains('id', $permissions->id);
        }
        return (bool) !!$permissions->intersect($this->permission)->count();
    }

    public function isSuperAdmin()
    {
        if($this->hasRole('super_admin')) return true;
        return null;
    }

    public function hasPermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = \Quantum\base\Models\Permission::findByName($permission);
        }
        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    protected function hasDirectPermission(Permission $permission)
    {
        if (is_string($permission)) {
            $permission = \Quantum\base\Models\Permission::where('name',$permission)->first();
        }
        return $this->hasPermission($permission);
    }

    protected function hasPermissionViaRole(Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }

    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = \Quantum\base\Models\Permission::where('name',$permission)->first();
        }
        if(is_object($permission))$this->permission()->save($permission);
    }
}