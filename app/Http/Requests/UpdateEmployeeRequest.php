<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $employee = $this->route('employee');

        return $employee instanceof Employee
            && $this->user()->can('update', $employee);
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'employee_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_code')
                    ->ignore($employee->id),
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email')
                    ->ignore($employee->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'birth_date' => [
                'nullable',
                'date',
                'before:today',
            ],

            'gender' => [
                'required',
                Rule::in(['male', 'female', 'other']),
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'position_id' => [
                'required',
                Rule::exists('positions', 'id')
                    ->where('department_id', $this->input('department_id')),
            ],

            'manager_id' => [
                'nullable',
                Rule::exists('employees', 'id')
                    ->whereNot('id', $employee->id),
            ],

            'hire_date' => [
                'required',
                'date',
            ],

            'employment_type' => [
                'required',
                Rule::in([
                    'permanent',
                    'contract',
                    'internship',
                    'probation',
                ]),
            ],

            'employment_status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'resigned',
                    'terminated',
                ]),
            ],
        ];
    }
}