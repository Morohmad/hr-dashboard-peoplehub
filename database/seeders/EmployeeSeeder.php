<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            return;
        }

        // Create 40 employee records
        Employee::factory()->count(40)->create();

        // Assign demo manager to an employee profile
        $managerUser = User::where(
            'email',
            'manager@peoplehub.test'
        )->first();

        $managerDepartment = Department::where(
            'code',
            'IT'
        )->first();

        $managerPosition = Position::where(
            'name',
            'IT Manager'
        )->first();

        if ($managerUser && $managerDepartment && $managerPosition) {
            $managerEmployee = Employee::where(
                'employee_code',
                'EMP-0001'
            )->first();

            if (!$managerEmployee) {
                $managerEmployee = Employee::create([
                    'employee_code' => 'EMP-0001',
                    'first_name' => 'Department',
                    'last_name' => 'Manager',
                    'email' => 'manager.employee@peoplehub.test',
                    'department_id' => $managerDepartment->id,
                    'position_id' => $managerPosition->id,
                    'hire_date' => now()->subYears(3),
                    'employment_type' => 'permanent',
                    'employment_status' => 'active',
                ]);
            }

            $managerEmployee->update([
                'user_id' => $managerUser->id,
                'department_id' => $managerDepartment->id,
                'position_id' => $managerPosition->id,
            ]);
        }

        // Link HR demo account to an employee
        $hrUser = User::where(
            'email',
            'hr@peoplehub.test'
        )->first();

        if ($hrUser) {
            $hrDepartment = Department::where('code', 'HR')->first();

            $hrPosition = Position::where(
                'name',
                'HR Officer'
            )->first();

            if ($hrDepartment && $hrPosition) {
                Employee::updateOrCreate(
                    ['employee_code' => 'EMP-HR-001'],
                    [
                        'user_id' => $hrUser->id,
                        'first_name' => 'HR',
                        'last_name' => 'Administrator',
                        'email' => 'hr.employee@peoplehub.test',
                        'department_id' => $hrDepartment->id,
                        'position_id' => $hrPosition->id,
                        'hire_date' => now()->subYears(2),
                        'employment_type' => 'permanent',
                        'employment_status' => 'active',
                    ]
                );
            }
        }

        // Link employee demo account
        $employeeUser = User::where(
            'email',
            'employee@peoplehub.test'
        )->first();

        if ($employeeUser) {
            $employee = Employee::where(
                'employee_code',
                'EMP-0002'
            )->first();

            if ($employee) {
                $employee->update([
                    'user_id' => $employeeUser->id,
                ]);
            }
        }

        // Assign subordinates to manager
        $managerEmployee = Employee::where(
            'employee_code',
            'EMP-0001'
        )->first();

        if ($managerEmployee) {
            Employee::whereNull('manager_id')
                ->where('id', '!=', $managerEmployee->id)
                ->where('department_id', $managerEmployee->department_id)
                ->limit(5)
                ->update([
                    'manager_id' => $managerEmployee->id,
                ]);
        }
    }
}