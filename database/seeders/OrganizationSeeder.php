<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            'IT' => [
                'name' => 'Information Technology',
                'positions' => [
                    ['name' => 'IT Manager', 'level' => 'Manager'],
                    ['name' => 'Software Engineer', 'level' => 'Staff'],
                    ['name' => 'IT Support', 'level' => 'Staff'],
                    ['name' => 'System Administrator', 'level' => 'Staff'],
                ],
            ],

            'HR' => [
                'name' => 'Human Resources',
                'positions' => [
                    ['name' => 'HR Manager', 'level' => 'Manager'],
                    ['name' => 'HR Officer', 'level' => 'Staff'],
                    ['name' => 'Recruitment Officer', 'level' => 'Staff'],
                ],
            ],

            'FIN' => [
                'name' => 'Finance',
                'positions' => [
                    ['name' => 'Finance Manager', 'level' => 'Manager'],
                    ['name' => 'Accounting Staff', 'level' => 'Staff'],
                    ['name' => 'Finance Analyst', 'level' => 'Staff'],
                ],
            ],

            'MKT' => [
                'name' => 'Marketing',
                'positions' => [
                    ['name' => 'Marketing Manager', 'level' => 'Manager'],
                    ['name' => 'Digital Marketing Specialist', 'level' => 'Staff'],
                    ['name' => 'Marketing Staff', 'level' => 'Staff'],
                ],
            ],

            'OPS' => [
                'name' => 'Operations',
                'positions' => [
                    ['name' => 'Operations Manager', 'level' => 'Manager'],
                    ['name' => 'Operations Officer', 'level' => 'Staff'],
                    ['name' => 'Operations Staff', 'level' => 'Staff'],
                ],
            ],
        ];

        foreach ($organizations as $code => $data) {
            $department = Department::firstOrCreate(
                ['code' => $code],
                [
                    'name' => $data['name'],
                    'description' => $data['name'] . ' Department',
                    'is_active' => true,
                ]
            );

            foreach ($data['positions'] as $position) {
                Position::firstOrCreate(
                    [
                        'department_id' => $department->id,
                        'name' => $position['name'],
                    ],
                    [
                        'level' => $position['level'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}