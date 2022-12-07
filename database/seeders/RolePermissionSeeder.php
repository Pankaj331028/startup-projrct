<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * for populating new role or permission, empty the below tables from database
     * roles, permissions, and role_has_permissions
     *
     * and run this seeder like php artisan db:seed --class=RolePermissionSeeder
     *
     *
     * @return void
     */
    public function run()
    {
        $rolePermissions = config('role-permission', []);
        foreach($rolePermissions as $roleName => $permissions) {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            if(!strcasecmp($roleName, 'admin')) {
                // create permissions from admin because admin can have all permissions
                foreach($permissions as $permission) {
                    Permission::create([
                        'name' => $permission,
                        'guard_name' => 'web'
                    ]);
                }
            }

            $role->givePermissionTo($permissions);
        }
    }
}
