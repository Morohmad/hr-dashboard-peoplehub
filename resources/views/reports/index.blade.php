
@extends('layouts.peoplehub')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <p class="text-sm text-gray-500 mt-1">
            View, filter, and export employee, attendance, and leave reports.
        </p>
    </div>

    {{-- Report Selection --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <a href="{{ route('reports.index', ['report' => 'employees']) }}"
           class="bg-white border rounded-xl p-5 shadow-sm transition
           {{ $reportType === 'employees' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-200 hover:border-blue-300' }}">

            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.196-2.044M17 20H7m10 0v-2c0-.653-.126-1.277-.354-1.854M7 20H2v-2a3 3 0 015.196-2.044M7 20v-2c0-.653.126-1.277.354-1.854m0 0a5.002 5.002 0 019.292 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>

                @if ($reportType === 'employees')
                    <span class="text-xs font-semibold text-blue-600">Selected</span>
                @endif
            </div>

            <h3 class="text-base font-semibold text-gray-900 mt-4">Employee Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                Employee profiles, departments, positions, and employment status.
            </p>
        </a>

        <a href="{{ route('reports.index', ['report' => 'attendance']) }}"
           class="bg-white border rounded-xl p-5 shadow-sm transition
           {{ $reportType === 'attendance' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-200 hover:border-blue-300' }}">

            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                @if ($reportType === 'attendance')
                    <span class="text-xs font-semibold text-blue-600">Selected</span>
                @endif
            </div>

            <h3 class="text-base font-semibold text-gray-900 mt-4">Attendance Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                Check-in, check-out, attendance dates, and attendance status.
            </p>
        </a>

        <a href="{{ route('reports.index', ['report' => 'leaves']) }}"
           class="bg-white border rounded-xl p-5 shadow-sm transition
           {{ $reportType === 'leaves' ? 'border-blue-500 ring-2 ring-blue-100' : 'border-gray-200 hover:border-blue-300' }}">

            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                @if ($reportType === 'leaves')
                    <span class="text-xs font-semibold text-blue-600">Selected</span>
                @endif
            </div>

            <h3 class="text-base font-semibold text-gray-900 mt-4">Leave Report</h3>
            <p class="text-sm text-gray-500 mt-1">
                Leave requests, duration, leave types, and approval status.
            </p>
        </a>

    </div>

    {{-- Report Table --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        {{-- Table Header --}}
        <div class="p-5 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    @switch($reportType)
                        @case('employees')
                            Employee Report
                            @break
                        @case('attendance')
                            Attendance Report
                            @break
                        @case('leaves')
                            Leave Report
                            @break
                    @endswitch
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Review report data and export it to CSV.
                </p>
            </div>

            <a href="{{ route('reports.export', request()->query()) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                </svg>

                Export CSV
            </a>
        </div>

        {{-- Filters --}}
        @if (in_array($reportType, ['attendance', 'leaves']))
            <form method="GET" action="{{ route('reports.index') }}"
                  class="p-5 bg-gray-50 border-b border-gray-200">

                <input type="hidden" name="report" value="{{ $reportType }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Date From
                        </label>

                        <input type="date"
                               name="date_from"
                               value="{{ $dateFrom }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Date To
                        </label>

                        <input type="date"
                               name="date_to"
                               value="{{ $dateTo }}"
                               min="{{ $dateFrom }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            Apply Filter
                        </button>

                        <a href="{{ route('reports.index', ['report' => $reportType]) }}"
                           class="px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm rounded-lg">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        @endif

        {{-- Employee Table --}}
        @if ($reportType === 'employees')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Position</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hire Date</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employment Status</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($employees as $employee)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $employee->employee_code }}</p>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $employee->department?->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $employee->position?->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $employee->hire_date?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ $employee->employment_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($employee->employment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500">
                                    No employee records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-100">
                {{ $employees->links() }}
            </div>
        @endif

        {{-- Attendance Table --}}
        @if ($reportType === 'attendance')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check In</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check Out</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($attendanceRecords as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $record->employee?->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->employee?->employee_code }}</p>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $record->employee?->department?->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $record->attendance_date?->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $record->check_in?->format('H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $record->check_out?->format('H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ $record->status === 'present' ? 'bg-green-100 text-green-700' : ($record->status === 'late' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-100">
                {{ $attendanceRecords->links() }}
            </div>
        @endif

        {{-- Leave Table --}}
        @if ($reportType === 'leaves')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Duration</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Days</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($leaveRecords as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $record->employee?->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->employee?->employee_code }}</p>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ ucfirst($record->leave_type) }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $record->start_date?->format('d M Y') }}
                                    –
                                    {{ $record->end_date?->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $record->total_days }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ $record->status === 'approved' ? 'bg-green-100 text-green-700' : ($record->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500">
                                    No leave records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-100">
                {{ $leaveRecords->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
