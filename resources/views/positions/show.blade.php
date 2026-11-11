
@extends('layouts.peoplehub')

@section('title', 'Position Details')
@section('page-heading', 'Position Details')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">
                {{ $position->name }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                {{ $position->department?->name ?? 'No Department' }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('positions.index') }}"
               class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Back
            </a>

            @can('positions.edit')
                <a href="{{ route('positions.edit', $position) }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Edit Position
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Department</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">
                {{ $position->department?->name ?? '-' }}
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Position Level</p>
            <p class="text-lg font-semibold text-slate-800 mt-2">
                {{ $position->level ?: '-' }}
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Assigned Employees</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ $position->employees_count }}
            </p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <div class="flex items-center justify-between gap-3">
            <h3 class="font-semibold text-slate-800">Position Status</h3>

            @if ($position->is_active)
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    Active
                </span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                    Inactive
                </span>
            @endif
        </div>

        <div class="mt-5 border-t border-slate-100 pt-5">
            <h3 class="font-semibold text-slate-800 mb-2">Description</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
                {{ $position->description ?: 'No description available.' }}
            </p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Assigned Employees</h3>
            <p class="text-xs text-slate-500 mt-1">
                Employees currently assigned to this position.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Employee Code</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Manager</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-slate-50">
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
                                {{ $employee->department?->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $employee->manager?->full_name ?? '-' }}
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
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No employees assigned to this position.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $employees->links() }}
        </div>
    </div>

</div>
@endsection
