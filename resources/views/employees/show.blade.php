
@extends('layouts.peoplehub')

@section('title', 'Employee Details')

@section('page-heading', 'Employee Details')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Employee Details
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                View complete employee information and employment records.
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('employees.index') }}"
               class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                Back
            </a>

            @can('update', $employee)
                <a href="{{ route('employees.edit', $employee) }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Edit Employee
                </a>
            @endcan

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Employee Profile Card --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-white/30 bg-white text-2xl font-bold text-indigo-700">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name ?? '', 0, 1)) }}
                </div>

                <div class="text-white">

                    <h2 class="text-2xl font-bold">
                        {{ $employee->full_name }}
                    </h2>

                    <p class="mt-1 text-sm text-indigo-100">
                        {{ $employee->position?->name ?? 'No position assigned' }}
                    </p>

                    <p class="mt-1 text-sm text-indigo-100">
                        {{ $employee->department?->name ?? 'No department assigned' }}
                    </p>

                </div>

                <div class="sm:ml-auto">

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

                    <span class="inline-flex rounded-full px-3 py-1.5 text-sm font-semibold {{ $statusClass }}">
                        {{ ucfirst($employee->employment_status) }}
                    </span>

                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-3">

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Employee ID
                </p>
                <p class="mt-1 font-semibold text-slate-900">
                    {{ $employee->employee_code }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Email Address
                </p>
                <p class="mt-1 break-all font-semibold text-slate-900">
                    {{ $employee->email }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Phone Number
                </p>
                <p class="mt-1 font-semibold text-slate-900">
                    {{ $employee->phone ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    {{-- Information Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Personal Information --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Personal Information
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Employee personal details.
                </p>
            </div>

            <div class="divide-y divide-slate-100 px-6">

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">First Name</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->first_name }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Last Name</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->last_name ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Date of Birth</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->birth_date?->format('d M Y') ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Gender</span>
                    <span class="text-right text-sm font-medium capitalize text-slate-900">
                        {{ $employee->gender ?? '-' }}
                    </span>
                </div>

                <div class="py-4">
                    <p class="text-sm text-slate-500">Address</p>
                    <p class="mt-2 text-sm font-medium text-slate-900">
                        {{ $employee->address ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Employment Information --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Employment Information
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Employee work and organization details.
                </p>
            </div>

            <div class="divide-y divide-slate-100 px-6">

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Department</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->department?->name ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Position</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->position?->name ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Manager</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->manager?->full_name ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Hire Date</span>
                    <span class="text-right text-sm font-medium text-slate-900">
                        {{ $employee->hire_date?->format('d M Y') ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Employment Type</span>
                    <span class="text-right text-sm font-medium capitalize text-slate-900">
                        {{ $employee->employment_type }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 py-4">
                    <span class="text-sm text-slate-500">Employment Status</span>
                    <span class="text-right text-sm font-medium capitalize text-slate-900">
                        {{ $employee->employment_status }}
                    </span>
                </div>

            </div>

        </div>

    </div>

    {{-- Subordinates --}}
    @if($employee->subordinates->isNotEmpty())
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Direct Reports
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Employees reporting directly to {{ $employee->full_name }}.
                </p>
            </div>

            <div class="divide-y divide-slate-100">

                @foreach($employee->subordinates as $subordinate)
                    <div class="flex items-center justify-between gap-4 px-6 py-4">

                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $subordinate->full_name }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $subordinate->position?->name ?? '-' }}
                            </p>
                        </div>

                        @can('view', $subordinate)
                            <a href="{{ route('employees.show', $subordinate) }}"
                               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                View
                            </a>
                        @endcan

                    </div>
                @endforeach

            </div>

        </div>
    @endif

</div>

@endsection
