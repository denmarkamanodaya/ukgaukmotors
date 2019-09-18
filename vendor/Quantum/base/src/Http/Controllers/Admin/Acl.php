<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Models\Permission;
use Quantum\base\Models\PermissionArea;
use Quantum\base\Models\PermissionGroup;
use Quantum\base\Models\Role;
use Quantum\base\Services\AclService;
use Quantum\base\Services\PermissionGroupService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Acl extends Controller
{

    /**
     * @var AclService
     */
    private $aclService;
    /**
     * @var PermissionGroupService
     */
    private $permissionGroupService;

    public function __construct(AclService $aclService, PermissionGroupService $permissionGroupService)
    {
        $this->aclService = $aclService;
        $this->permissionGroupService = $permissionGroupService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = \Quantum\base\Models\Role::where('name', '!=', 'super_admin')->get();
        //$permissionGroups = PermissionGroup::lists('title', 'id');
        //$permissions = PermissionGroup::with('permissions')->get();
        $permissions = PermissionArea::get();
        $permissionGroups = $this->permissionGroupService->areaGroupList($permissions);
        return view('base::admin.Acl.index', compact('roles', 'permissionGroups', 'permissions'));
    }


    public function roleStore(\Quantum\base\Http\Requests\Admin\CreateRoleRequest $createRoleRequest)
    {
        $role = $this->aclService->createRole($createRoleRequest);
        return redirect('admin/acl/role/'.$role->id.'/edit');
    }

    public function roleEdit($id, PermissionGroupService $permissionGroupService)
    {
        $role = Role::with('permissions')->whereId($id)->firstOrFail();
        //$permissions = $permissionGroupService->groupList();
        $permissions = PermissionArea::get();;
        return view('base::admin.Acl.roleEdit', compact('role', 'permissions'));
    }

    public function roleUpdate($id, \Quantum\base\Http\Requests\Admin\UpdateRoleRequest $updateRoleRequest)
    {
        $this->aclService->updateRole($id, $updateRoleRequest);
        return redirect('admin/acl/role/'.$id.'/edit');
    }
    
    public function roleDestroy($id)
    {
        $this->aclService->destroyRole($id);
        return redirect('admin/acl');
    }

    public function permissionCreate(\Quantum\base\Http\Requests\Admin\CreatePermissionRequest $createPermissionRequest)
    {
        $this->aclService->createPermission($createPermissionRequest);
        return redirect('admin/acl');
    }

    public function permissionEdit($id)
    {
        $permission = Permission::where('system', 0)->findOrFail($id);
        //$permissionGroups = PermissionGroup::lists('title', 'id');
        $permissionGroups = $this->permissionGroupService->areaGroupList();
        return view('base::admin.Acl.permissionEdit', compact('permission', 'permissionGroups'));
    }

    public function permissionUpdate($id, \Quantum\base\Http\Requests\Admin\UpdatePermissionRequest $updatePermissionRequest )
    {
        $this->aclService->updatePermission($id, $updatePermissionRequest);
        return redirect('admin/acl');
    }

    public function permissionDestroy($id)
    {
        $this->aclService->destroyPermission($id);
        return redirect('admin/acl');
    }

}
