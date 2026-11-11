<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PositionPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'positions.view',
            'positions.create',
            'positions.edit',
            'positions.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Super Admin: full access
        $superAdmin = Role::findByName('Super Admin', 'web');
        $superAdmin->givePermissionTo($permissions);

        // HR Admin: view, create, and edit
        $hrAdmin = Role::findByName('HR Admin', 'web');
        $hrAdmin->givePermissionTo([
            'positions.view',
            'positions.create',
            'positions.edit',
        ]);

        // Manager: view only
        $manager = Role::findByName('Manager', 'web');
        $manager->givePermissionTo([
            'positions.view',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
