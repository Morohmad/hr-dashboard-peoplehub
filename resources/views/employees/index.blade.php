
@extends('layouts.peoplehub')

@section('title', 'Employees')

@section('page-heading', 'Employee Management')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Employee Management
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Manage employee information and organization records.
            </p>
        </div>

        @can('employees.create')
            <a href="{{ route('employees.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">

                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>

                Add Employee
            </a>
        @endcan
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Employees --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Total Employees
                </p>

                <div class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-3xl font-bold text-gray-900">
                {{ number_format($totalEmployees) }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Registered employees
            </p>
        </div>

        {{-- Active Employees --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Active Employees
                </p>

                <div class="rounded-lg bg-green-50 p-2 text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-3xl font-bold text-gray-900">
                {{ number_format($activeEmployees) }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Currently working
            </p>
        </div>

        {{-- Inactive Employees --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Inactive Employees
                </p>

                <div class="rounded-lg bg-amber-50 p-2 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-3xl font-bold text-gray-900">
                {{ number_format($inactiveEmployees) }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Currently inactive
            </p>
        </div>

        {{-- Resigned / Terminated --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Resigned / Terminated
                </p>

                <div class="rounded-lg bg-red-50 p-2 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-3xl font-bold text-gray-900">
                {{ number_format($resignedEmployees) }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Employment ended
            </p>
        </div>

    </div>

    {{-- Employee Directory --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Directory Header --}}
        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="text-base font-semibold text-gray-900">
                Employee Directory
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                View and manage your employee records.
            </p>
        </div>

        {{-- Search and Filters --}}
        <form method="GET"
              action="{{ route('employees.index') }}"
              class="grid grid-cols-1 gap-3 border-b border-gray-200 p-5 md:grid-cols-2 xl:grid-cols-4">

            {{-- Search --}}
            <div class="relative xl:col-span-2">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-width="2"
                          d="m21 21-4.35-4.35"/>
                </svg>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search name, email, employee ID..."
                       class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            </div>

            {{-- Department Filter --}}
            <select name="department_id"
                    class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                <option value="">All Departments</option>

                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                        @selected(request('department_id') == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach

            </select>

            {{-- Status Filter --}}
            <select name="employment_status"
                    class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                <option value="">All Status</option>

                <option value="active"
                    @selected(request('employment_status') === 'active')>
                    Active
                </option>

                <option value="inactive"
                    @selected(request('employment_status') === 'inactive')>
                    Inactive
                </option>

                <option value="resigned"
                    @selected(request('employment_status') === 'resigned')>
                    Resigned
                </option>

                <option value="terminated"
                    @selected(request('employment_status') === 'terminated')>
                    Terminated
                </option>

            </select>

            {{-- Filter Buttons --}}
            <div class="flex flex-wrap gap-2 md:col-span-2 xl:col-span-4">

                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    Apply Filters
                </button>

                <a href="{{ route('employees.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                    Reset
                </a>

            </div>

        </form>

        {{-- Employee Table --}}
        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Employee
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Employee ID
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Department
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Position
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($employees as $employee)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Employee Information --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name ?? '', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $employee->full_name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $employee->email }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Employee Code --}}
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-700">
                                {{ $employee->employee_code }}
                            </td>

                            {{-- Department --}}
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                {{ $employee->department?->name ?? '-' }}
                            </td>

                            {{-- Position --}}
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                {{ $employee->position?->name ?? '-' }}
                            </td>

                            {{-- Employment Status --}}
                            <td class="whitespace-nowrap px-5 py-4">

                                @php
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-700',
                                        'inactive' => 'bg-amber-100 text-amber-700',
                                        'resigned' => 'bg-gray-100 text-gray-700',
                                        'terminated' => 'bg-red-100 text-red-700',
                                    ];

                                    $statusClass = $statusClasses[$employee->employment_status]
                                        ?? 'bg-gray-100 text-gray-700';
                                @endphp

                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($employee->employment_status) }}
                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right">

                                <div class="flex items-center justify-end gap-3">

                                    @can('view', $employee)
                                        <a href="{{ route('employees.show', $employee) }}"
                                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                            View
                                        </a>
                                    @endcan

                                    @can('update', $employee)
                                        <a href="{{ route('employees.edit', $employee) }}"
                                           class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete', $employee)
                                        <form method="POST"
                                              action="{{ route('employees.destroy', $employee) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this employee?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-sm font-medium text-red-600 hover:text-red-800">
                                                Delete
                                            </button>

                                        </form>
                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- Empty State --}}
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <svg class="h-6 w-6" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                        <path stroke-linecap="round" stroke-width="2"
                                              d="m21 21-4.35-4.35"/>
                                    </svg>
                                </div>

                                <p class="mt-3 text-sm font-semibold text-gray-900">
                                    No employees found
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Try adjusting your search or filter.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($employees->hasPages() || $employees->total() > 0)

            <div class="border-t border-gray-200 px-5 py-4">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-sm text-gray-500">
                        Showing

                        <span class="font-semibold text-gray-900">
                            {{ $employees->firstItem() ?? 0 }}
                        </span>

                        to

                        <span class="font-semibold text-gray-900">
                            {{ $employees->lastItem() ?? 0 }}
                        </span>

                        of

                        <span class="font-semibold text-gray-900">
                            {{ $employees->total() }}
                        </span>

                        employees
                    </p>

                    <div>
                        {{ $employees->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
