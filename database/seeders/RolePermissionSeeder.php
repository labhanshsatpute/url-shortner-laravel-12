<?php

namespace Database\Seeders;

use App\Enums\Permissions\CompanyPermission;
use App\Enums\Permissions\ShortUrlPermission;
use App\Enums\Permissions\UserPermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission_array = array_merge(
            UserPermission::values(),
            CompanyPermission::values(),
            ShortUrlPermission::values()
        );

        foreach ($permission_array as $value) {
            if (!Permission::where('name', $value)->exists()) {
               Permission::create(['name' => $value,'guard_name' => 'web']);
            }
        }

        $roles_array = ['SuperAdmin', 'Admin', 'Member'];

        foreach ($roles_array as $value) {
            if (!Role::where('name', $value)->exists()) {
               Role::create(['name' => $value,'guard_name' => 'web']);
            }
        }

        $super_admin_permissions = Permission::whereIn('name', array_merge(
            UserPermission::values(), 
            CompanyPermission::values(),
            [ShortUrlPermission::VIEW_ALL_SHORT_URLS->value,]
        ))->get();

        $admin_permissions = Permission::whereIn('name', [
            UserPermission::INVITE_USER_TO_SELF_COMPANY->value,
            UserPermission::VIEW_SELF_COMPANY_USERS->value,
            ShortUrlPermission::SHORT_URL_CREATE->value,
            ShortUrlPermission::VIEW_COMPANY_SHORT_URL->value,
            ShortUrlPermission::VIEW_SELF_SHORT_URL->value,
        ])->get();

        $member_permissions = Permission::whereIn('name', [
            ShortUrlPermission::SHORT_URL_CREATE->value,
            ShortUrlPermission::VIEW_SELF_SHORT_URL->value,
        ])->get();

        $super_admin_role = Role::where('name', 'SuperAdmin')->first();
        $super_admin_role->syncPermissions($super_admin_permissions);


        $admin_role = Role::where('name', 'Admin')->first();
        $admin_role->syncPermissions($admin_permissions);

        $member_role = Role::where('name', 'Member')->first();
        $member_role->syncPermissions($member_permissions);
    }
}
