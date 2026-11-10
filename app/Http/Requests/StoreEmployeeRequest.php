<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('employees.create');
    }

    public function rules(): array
    {
        return [
            'employee_code' => [
                'required',
                'string',
                'max:50',
                'unique:employees,employee_code',
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
                'unique:employees,email',
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
                'exists:employees,id',
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