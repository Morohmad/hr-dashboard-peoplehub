
@extends('layouts.peoplehub')

@section('title', 'Attendance Management')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Attendance Management</h1>
            <p class="mt-1 text-sm text-slate-500">
                Monitor employee attendance and working hours.
            </p>
        </div>

        <div class="text-sm text-slate-500">
            {{ now()->format('l, d F Y') }}
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Today's Attendance --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Today's Status</p>

            <div class="mt-3">
                @if ($todayAttendance)
                    @if ($todayAttendance->status === 'late')
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                            Late
                        </span>
                    @else
                        <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">
                            Present
                        </span>
                    @endif
                @else
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-600">
                        Not Checked In
                    </span>
                @endif
            </div>

            <p class="mt-3 text-xs text-slate-400">
                Your attendance status today
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Present Today</p>
            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $presentCount }}
            </p>
            <p class="mt-2 text-xs text-slate-400">
                Employees marked present
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Late Today</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">
                {{ $lateCount }}
            </p>
            <p class="mt-2 text-xs text-slate-400">
                Employees marked late
            </p>
        </div>

    </div>

    {{-- Check In / Check Out --}}
    @if (!auth()->user()->can('attendance.manage'))
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">My Attendance</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Record your check-in and check-out for today.
                    </p>

                    @if ($todayAttendance)
                        <p class="mt-3 text-sm text-slate-600">
                            Check-in:
                            <strong>
                                {{ $todayAttendance->check_in?->format('H:i') ?? '-' }}
                            </strong>
                            <span class="mx-2 text-slate-300">|</span>
                            Check-out:
                            <strong>
                                {{ $todayAttendance->check_out?->format('H:i') ?? '-' }}
                            </strong>
                        </p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-3">
                    @if (!$todayAttendance || !$todayAttendance->check_in)
                        <form method="POST" action="{{ route('attendance.check-in') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                Check In
                            </button>
                        </form>
                    @elseif (!$todayAttendance->check_out)
                        <form method="POST" action="{{ route('attendance.check-out') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-900">
                                Check Out
                            </button>
                        </form>
                    @else
                        <span class="rounded-lg bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700">
                            Attendance Completed
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Attendance History --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800">Attendance Records</h2>
            <p class="mt-1 text-sm text-slate-500">
                View and filter attendance history.
            </p>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('attendance.index') }}"
            class="grid grid-cols-1 gap-3 border-b border-slate-100 bg-slate-50 p-5 md:grid-cols-4">

            @if (auth()->user()->can('attendance.manage'))
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search employee..."
                    class="rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            @endif

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">

            <select
                name="status"
                class="rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="present" @selected(request('status') === 'present')>Present</option>
                <option value="late" @selected(request('status') === 'late')>Late</option>
                <option value="absent" @selected(request('status') === 'absent')>Absent</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('attendance.index') }}"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Reset
                </a>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Employee
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Department
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Check In
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Check Out
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($attendances as $attendance)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $attendance->employee->full_name }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $attendance->employee->employee_code }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $attendance->employee->department?->name ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $attendance->attendance_date->format('d M Y') }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $attendance->check_in?->format('H:i') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $attendance->check_out?->format('H:i') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($attendance->status === 'present')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Present
                                    </span>
                                @elseif ($attendance->status === 'late')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Late
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="font-medium text-slate-600">No attendance records found</p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Attendance records will appear here once employees check in.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($attendances->hasPages())
            <div class="border-t border-slate-200 p-5">
                {{ $attendances->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
