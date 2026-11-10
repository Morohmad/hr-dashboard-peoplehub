<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',

            'departments.view',
            'departments.create',
            'departments.edit',
            'departments.delete',

            'attendance.view',
            'attendance.manage',

            'leaves.view',
            'leaves.create',
            'leaves.approve',

            'payroll.view',
            'payroll.manage',

            'reports.view',
            'reports.export',

            'users.view',
            'users.manage',

            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $allPermissions = Permission::where(
            'guard_name',
            'web'
        )->get();

        $roles = [
            'Super Admin' => $allPermissions->pluck('name')->all(),

            'HR Admin' => [
                'dashboard.view',
                'employees.view',
                'employees.create',
                'employees.edit',
                'departments.view',
                'departments.create',
                'departments.edit',
                'attendance.view',
                'attendance.manage',
                'leaves.view',
                'leaves.approve',
                'payroll.view',
                'payroll.manage',
                'reports.view',
                'reports.export',
            ],

            'Manager' => [
                'dashboard.view',
                'employees.view',
                'attendance.view',
                'leaves.view',
                'leaves.approve',
                'reports.view',
            ],

            'Employee' => [
                'dashboard.view',
                'attendance.view',
                'leaves.view',
                'leaves.create',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($rolePermissions);
        }

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}