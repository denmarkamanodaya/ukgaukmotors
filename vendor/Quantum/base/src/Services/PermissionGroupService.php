<?php

namespace Quantum\base\Services;


use Quantum\base\Models\PermissionArea;
use Quantum\base\Models\PermissionGroup;

class PermissionGroupService
{

    public function groupList()
    {
        $permlist = [];
        $permissions = PermissionGroup::with('permissions')->orderBy('permission_area_id', 'ASC')->get();
        foreach($permissions as $permission)
        {
            $permKey = ucwords(str_replace('-', ' ', $permission->name));
            $permlist[$permKey] = [];

            if(count($permission->permissions) > 0)
            {
                foreach($permission->permissions as $perm)
                {
                    $permlist[$permKey][$perm->name] = $perm->title;
                }
            }

        }
        return $permlist;
    }

    public function areaGroupListAll($areaList = false)
    {
        $areaGroupList = [];
        if(!$areaList) $areaList = PermissionArea::get();

        foreach($areaList as $area)
        {
            if(count($area->permissiongroups) > 0)
            {
                foreach($area->permissiongroups as $group)
                {
                    $areaGroupList[$area->title.' - '.$group->title] = [];
                    if(count($group->permissions) > 0)
                    {
                        foreach($group->permissions as $permission)
                        {
                            $areaGroupList[$area->title.' - '.$group->title][$permission->id] = $permission->title;
                        }

                    }
                }

            }
        }
        return $areaGroupList;
    }

    public function areaGroupListAllNice($areaList = false)
    {
        $areaGroupList = [];
        if(!$areaList) $areaList = PermissionArea::get();

        foreach($areaList as $area)
        {
            $areaGroupList[$area->title] = [];
            if(count($area->permissiongroups) > 0)
            {
                foreach($area->permissiongroups as $group)
                {
                    $areaGroupList[$area->title][$group->title] = [];
                    if(count($group->permissions) > 0)
                    {
                        foreach($group->permissions as $permission)
                        {
                            $areaGroupList[$area->title][$group->title][$permission->id] = $permission->title;
                        }

                    }
                }

            }
        }
        return $areaGroupList;
    }

    public function areaGroupList($areaList = false)
    {
        $areaGroupList = [];
        if(!$areaList) $areaList = PermissionArea::get();

        foreach($areaList as $area)
        {
            $areaGroupList[$area->title] = [];
            if(count($area->permissiongroups) > 0)
            {
                foreach($area->permissiongroups as $group)
                {
                    $areaGroupList[$area->title][$group->id] = $group->title;

                }

            }
        }
        return $areaGroupList;
    }

}