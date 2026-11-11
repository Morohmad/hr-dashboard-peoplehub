
@extends('layouts.peoplehub')

@section('title', 'Department Details')
@section('page-heading', 'Department Details')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">
                {{ $department->name }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Department Code: {{ $department->code }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('departments.index') }}"
               class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Back
            </a>

            @can('departments.edit')
                <a href="{{ route('departments.edit', $department) }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Edit Department
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Employees</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ $department->employees_count }}
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Positions</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ $department->positions_count }}
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Department Status</p>
            <div class="mt-3">
                @if ($department->is_active)
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        Active
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                        Inactive
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <h3 class="font-semibold text-slate-800 mb-3">Department Description</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
            {{ $department->description ?: 'No description available.' }}
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Department Positions</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">Position</th>
                        <th class="px-6 py-3">Level</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($department->positions as $position)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $position->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $position->level ?: '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($position->is_active)
                                    <span class="text-green-700">Active</span>
                                @else
                                    <span class="text-slate-500">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                No positions found in this department.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Department Employees</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">Employee</th>
                        <th class="px-6 py-3">Employee Code</th>
                        <th class="px-6 py-3">Position</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($department->employees as $employee)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $employee->full_name }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $employee->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $employee->employee_code }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $employee->position?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($employee->employment_status === 'active')
                                    <span class="text-green-700">Active</span>
                                @else
                                    <span class="text-slate-500">
                                        {{ ucfirst($employee->employment_status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                No employees found in this department.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
