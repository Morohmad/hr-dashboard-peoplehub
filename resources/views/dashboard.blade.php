
@extends('layouts.peoplehub')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Dashboard
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Welcome back, {{ auth()->user()->name }}. Here's your HR overview.
            </p>
        </div>

        <div class="text-sm text-slate-500">
            {{ now()->format('l, d F Y') }}
        </div>
    </div>

    {{-- Employee Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Employees --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Employees</p>

            <div class="mt-3 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-slate-800">
                    {{ number_format($totalEmployees) }}
                </h2>

                <div class="rounded-lg bg-blue-50 p-3 text-blue-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H2v-2a4 4 0 014-4h3m6-5a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 10-6 0" />
                    </svg>
                </div>
            </div>

            <p class="mt-2 text-xs text-slate-400">Registered employees</p>
        </div>

        {{-- Active Employees --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Active Employees</p>

            <div class="mt-3 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-slate-800">
                    {{ number_format($activeEmployees) }}
                </h2>

                <div class="rounded-lg bg-emerald-50 p-3 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <p class="mt-2 text-xs text-slate-400">Currently employed</p>
        </div>

        {{-- Departments --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Departments</p>

            <div class="mt-3 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-slate-800">
                    {{ number_format($totalDepartments) }}
                </h2>

                <div class="rounded-lg bg-violet-50 p-3 text-violet-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V7l8-4v18m6 0V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01" />
                    </svg>
                </div>
            </div>

            <p class="mt-2 text-xs text-slate-400">Company departments</p>
        </div>

        {{-- Positions --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Positions</p>

            <div class="mt-3 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-slate-800">
                    {{ number_format($totalPositions) }}
                </h2>

                <div class="rounded-lg bg-amber-50 p-3 text-amber-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            <p class="mt-2 text-xs text-slate-400">Available job positions</p>
        </div>

    </div>

    {{-- Attendance and Leave Statistics --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Attendance Overview --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-800">
                        Attendance Overview
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Today's attendance summary
                    </p>
                </div>

                <a href="{{ route('attendance.index') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View Details
                </a>
            </div>

            <div class="mt-5 grid grid-cols-3 gap-3">

                <div class="rounded-lg bg-emerald-50 p-4">
                    <p class="text-xs text-emerald-700">Present</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ $presentToday }}
                    </p>
                </div>

                <div class="rounded-lg bg-amber-50 p-4">
                    <p class="text-xs text-amber-700">Late</p>
                    <p class="mt-2 text-2xl font-bold text-amber-700">
                        {{ $lateToday }}
                    </p>
                </div>

                <div class="rounded-lg bg-rose-50 p-4">
                    <p class="text-xs text-rose-700">Absent</p>
                    <p class="mt-2 text-2xl font-bold text-rose-700">
                        {{ $absentToday }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Leave Requests --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-800">
                        Leave Requests
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Leave request status summary
                    </p>
                </div>

                <a href="{{ route('leaves.index') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View Details
                </a>
            </div>

            <div class="mt-5 grid grid-cols-3 gap-3">

                <div class="rounded-lg bg-amber-50 p-4">
                    <p class="text-xs text-amber-700">Pending</p>
                    <p class="mt-2 text-2xl font-bold text-amber-700">
                        {{ $pendingLeaves }}
                    </p>
                </div>

                <div class="rounded-lg bg-emerald-50 p-4">
                    <p class="text-xs text-emerald-700">Approved</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ $approvedLeaves }}
                    </p>
                </div>

                <div class="rounded-lg bg-rose-50 p-4">
                    <p class="text-xs text-rose-700">Rejected</p>
                    <p class="mt-2 text-2xl font-bold text-rose-700">
                        {{ $rejectedLeaves }}
                    </p>
                </div>

            </div>
        </div>

    </div>

    {{-- Department Chart and Recent Leave Requests --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Employees by Department Chart --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                <div>
                    <h3 class="font-semibold text-slate-800">
                        Employees by Department
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Active employee distribution
                    </p>
                </div>

                <span class="text-xs text-slate-400">
                    {{ $activeEmployees }} Active Employees
                </span>
            </div>

            <div class="relative mt-5" style="height: 280px;">
                <canvas id="departmentChart"></canvas>
            </div>

            @if ($employeesByDepartment->isEmpty())
                <p class="mt-3 text-center text-sm text-slate-400">
                    No department data available.
                </p>
            @endif

        </div>

        {{-- Recent Leave Requests --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-800">
                        Recent Leave Requests
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Latest submitted requests
                    </p>
                </div>

                <a href="{{ route('leaves.index') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View All
                </a>
            </div>

            <div class="mt-4 divide-y divide-slate-100">

                @forelse ($recentLeaves as $leave)

                    <a href="{{ route('leaves.show', $leave) }}"
                       class="flex items-center justify-between gap-3 py-3 hover:bg-slate-50">

                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-slate-800">
                                {{ $leave->employee->full_name }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ ucfirst($leave->leave_type) }} ·
                                {{ $leave->start_date->format('d M Y') }}
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-medium
                            {{ $leave->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $leave->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $leave->status === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}">
                            {{ ucfirst($leave->status) }}
                        </span>

                    </a>

                @empty

                    <p class="py-8 text-center text-sm text-slate-400">
                        No recent leave requests.
                    </p>

                @endforelse

            </div>
        </div>

    </div>

    {{-- Today's Attendance --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-800">
                    Today's Attendance
                </h3>
                <p class="mt-1 text-xs text-slate-500">
                    Latest employee check-in activity
                </p>
            </div>

            <a href="{{ route('attendance.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-700">
                View All
            </a>
        </div>

        <div class="mt-4 overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-3 py-3">Employee</th>
                        <th class="px-3 py-3">Department</th>
                        <th class="px-3 py-3">Check In</th>
                        <th class="px-3 py-3">Check Out</th>
                        <th class="px-3 py-3">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($recentAttendances as $attendance)

                        <tr class="text-sm">

                            <td class="px-3 py-3 font-medium text-slate-800">
                                {{ $attendance->employee->full_name }}
                            </td>

                            <td class="px-3 py-3 text-slate-500">
                                {{ $attendance->employee->department->name ?? '-' }}
                            </td>

                            <td class="px-3 py-3 text-slate-600">
                                {{ $attendance->check_in?->format('H:i') ?? '-' }}
                            </td>

                            <td class="px-3 py-3 text-slate-600">
                                {{ $attendance->check_out?->format('H:i') ?? '-' }}
                            </td>

                            <td class="px-3 py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                    {{ $attendance->status === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $attendance->status === 'late' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $attendance->status === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="px-3 py-8 text-center text-sm text-slate-400">
                                No attendance records for today.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const canvas = document.getElementById('departmentChart');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const labels = @json($employeesByDepartment->pluck('name')->values());

        const employeeCounts = @json(
            $employeesByDepartment->pluck('employees_count')->values()
        );

        new Chart(canvas, {
            type: 'bar',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Active Employees',
                    data: employeeCounts,
                    backgroundColor: '#2563eb',
                    hoverBackgroundColor: '#1d4ed8',
                    borderRadius: 6,
                    barThickness: 28,
                    maxBarThickness: 36
                }]
            },

            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        displayColors: false,

                        callbacks: {
                            label: function (context) {
                                return context.parsed.x + ' Employees';
                            }
                        }
                    }
                },

                scales: {
                    x: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        },

                        grid: {
                            color: '#f1f5f9'
                        }
                    },

                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

    });
</script>
