<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;

class ACLTableSeeder extends Seeder {
    public function run() {

        $permissionArea = \Quantum\base\Models\PermissionArea::firstOrCreate( [
            'name' => 'admin' ,
            'title' => 'Admin Area',
            'position' => 1,
            'system' => 1
        ] );

        $permissionArea = \Quantum\base\Models\PermissionArea::firstOrCreate( [
            'name' => 'members' ,
            'title' => 'Members Area',
            'position' => 2,
            'system' => 1
        ] );

        $permissionArea = \Quantum\base\Models\PermissionArea::firstOrCreate( [
            'name' => 'public' ,
            'title' => 'Public Area',
            'position' => 3,
            'system' => 1
        ] );

        $permissionGroup = \Quantum\base\Models\PermissionGroup::firstOrCreate( [
            'name' => 'admin-general' ,
            'title' => 'General',
            'permission_area_id' => 1,
            'position' => 1,
            'system' => 1
        ] );

        $permissionGroup = \Quantum\base\Models\PermissionGroup::firstOrCreate( [
            'name' => 'members-general' ,
            'title' => 'General',
            'permission_area_id' => 2,
            'position' => 1,
            'system' => 1
        ] );

        $permissionGroup = \Quantum\base\Models\PermissionGroup::firstOrCreate( [
            'name' => 'public-general' ,
            'title' => 'General',
            'permission_area_id' => 3,
            'position' => 1,
            'system' => 1
        ] );

        $permissionGroup = \Quantum\base\Models\PermissionGroup::firstOrCreate( [
            'name' => 'admin-acl' ,
            'title' => 'Acl',
            'permission_area_id' => 1,
            'position' => 2,
            'system' => 1
        ] );

        $permissionGroup = \Quantum\base\Models\PermissionGroup::firstOrCreate( [
            'name' => 'admin-menu' ,
            'title' => 'Menu',
            'permission_area_id' => 1,
            'position' => 3,
            'system' => 1
        ] );

        $permission = \Quantum\base\Models\Permission::firstOrCreate( [
            'name' => 'view-admin-area' ,
            'title' => 'View Admin Area',
            'permission_group_id' => '1',
            'system' => 1,
            'position' => '0'
        ] );

        $permission = \Quantum\base\Models\Permission::firstOrCreate( [
            'name' => 'manage-acl' ,
            'title' => 'Manage Acl',
            'permission_group_id' => '4',
            'system' => 1,
            'position' => '1'
        ] );

        $permission = \Quantum\base\Models\Permission::firstOrCreate( [
            'name' => 'manage-menu' ,
            'title' => 'Manage Menu\'s',
            'permission_group_id' => '5',
            'system' => 1,
            'position' => '1'
        ] );

        $permission = \Quantum\base\Models\Permission::firstOrCreate( [
            'name' => 'view-members-area' ,
            'title' => 'View Members Area',
            'permission_group_id' => '2',
            'system' => 1,
            'position' => '1'
        ] );

        $permission = \Quantum\base\Models\Permission::firstOrCreate( [
            'name' => 'view-public-area' ,
            'title' => 'View Public Area',
            'permission_group_id' => '3',
            'system' => 1,
            'position' => '1'
        ] );


        $role = \Quantum\base\Models\Role::firstOrCreate( [
            'name' => 'super_admin' ,
            'title' => 'Super Admin'
        ] );

        $role = \Quantum\base\Models\Role::firstOrCreate( [
            'name' => 'admin' ,
            'title' => 'Admin'
        ] );

        $role->givePermissionTo('view-admin-area');
        $role->givePermissionTo('manage-acl');
        $role->givePermissionTo('manage-menu');

        $role = \Quantum\base\Models\Role::firstOrCreate( [
            'name' => 'member' ,
            'title' => 'Member'
        ] );

        $role->givePermissionTo('view-members-area');

        $role = \Quantum\base\Models\Role::firstOrCreate( [
            'name' => 'guest' ,
            'title' => 'Guest'
        ] );

        $role->givePermissionTo('view-public-area');

    }
}
