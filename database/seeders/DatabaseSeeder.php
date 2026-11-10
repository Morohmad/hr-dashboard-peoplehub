<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create roles and permissions
        $this->call(RolePermissionSeeder::class);

        // 2. Create demo accounts
        $accounts = [
            [
                'name' => 'Super Administrator',
                'email' => 'admin@peoplehub.test',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'HR Administrator',
                'email' => 'hr@peoplehub.test',
                'role' => 'HR Admin',
            ],
            [
                'name' => 'Department Manager',
                'email' => 'manager@peoplehub.test',
                'role' => 'Manager',
            ],
            [
                'name' => 'Demo Employee',
                'email' => 'employee@peoplehub.test',
                'role' => 'Employee',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make('PeopleHub123!'),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$account['role']]);
        }

        // 3. Create departments and positions
        $this->call(OrganizationSeeder::class);

        // 4. Create employee dummy data
        $this->call(EmployeeSeeder::class);
    }
}