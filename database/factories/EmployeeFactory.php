<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $department = Department::query()
            ->inRandomOrder()
            ->firstOrFail();

        $position = Position::query()
            ->where('department_id', $department->id)
            ->inRandomOrder()
            ->firstOrFail();

        return [
            'user_id' => null,
            'employee_code' => fake()->unique()->numerify('TEMP-####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08##########'),

            'birth_date' => fake()->dateTimeBetween(
                '-55 years',
                '-22 years'
            )->format('Y-m-d'),

            'gender' => fake()->randomElement([
                'male',
                'female',
            ]),

            'address' => fake()->address(),
            'department_id' => $department->id,
            'position_id' => $position->id,
            'manager_id' => null,

            'hire_date' => fake()->dateTimeBetween(
                '-8 years',
                'now'
            )->format('Y-m-d'),

            'employment_type' => fake()->randomElement([
                'permanent',
                'contract',
                'internship',
                'probation',
            ]),

            'employment_status' => fake()->randomElement([
                'active',
                'active',
                'active',
                'inactive',
                'resigned',
            ]),

            'photo' => null,
        ];
    }
}