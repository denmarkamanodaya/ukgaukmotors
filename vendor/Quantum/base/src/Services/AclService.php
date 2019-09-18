<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : AclService.php
 **/

namespace Quantum\base\Services;


use Quantum\base\Models\Permission;
use Quantum\base\Models\PermissionGroup;
use Quantum\base\Models\Role;

class AclService
{

    /**
     * @var MenuService
     */
    private $menuService;

    public function __construct()
    {
        $this->menuService = new MenuService();
    }

    public function createRole($request)
    {
        $role = Role::create($request->all());
        $this->forgetCachedPermissions();
        return $role;
    }

    public function updateRole($id, $request)
    {
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->title = $request->input('title');
        $role->save();

        $newPerms = $request->input('permissions') ? $request->input('permissions') : [];
        $role->permissions()->sync($newPerms);
        $this->menuService->clearCacheAll();
        $this->forgetCachedPermissions();
        flash('Success : The role has been updated.')->success();
        return $role;
    }
    
    public function destroyRole($id)
    {
        $role = Role::where('id',$id)->where('system', '0')->firstOrFail();
        $role->delete();
        $this->forgetCachedPermissions();
        flash('Success : The role has been deleted.')->success();
        return true;
    }

    public function createPermission($request)
    {
        $permission = Permission::create($request->all());
        $this->forgetCachedPermissions();
        return $permission;
    }

    public function updatePermission($id, $request)
    {
        $permisison = Permission::find($id);
        $permisison->name = $request->input('name');
        $permisison->title = $request->input('title');
        $permisison->permission_group_id = $request->input('permission_group_id');
        $permisison->save();
        flash('Success : The permission has been updated.')->success();
        $this->menuService->clearCacheAll();
        $this->forgetCachedPermissions();
        return $permisison;
    }

    public function destroyPermission($id)
    {
        $permisison = Permission::where('system', 0)->findOrFail($id);
        $permisison->delete();
        flash('Success : The permission has been removed.')->success();
        $this->menuService->clearCacheAll();
        $this->forgetCachedPermissions();
        return $permisison;
    }

    protected function forgetCachedPermissions()
    {
        \Cache::forget('acl_permissions');
        \Cache::forget('roles');
    }

    public function getAllRoles()
    {

        try {
            return \Cache::rememberForever('roles', function () {
                return Role::all();
            });
        } catch (\Exception $e) {
        }
    }

}