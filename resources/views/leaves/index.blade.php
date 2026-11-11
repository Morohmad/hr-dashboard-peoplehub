
@extends('layouts.peoplehub')

@section('title', 'Leave Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Leave Management</h1>
            <p class="mt-1 text-sm text-slate-500">
                Manage employee leave requests and approvals.
            </p>
        </div>

        @can('leaves.create')
            <a href="{{ route('leaves.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                + Request Leave
            </a>
        @endcan
    </div>

    {{-- Notifications --}}
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

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Requests</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">{{ $totalRequests }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pending</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $pendingRequests }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Approved</p>
            <p class="mt-2 text-3xl font-bold text-green-600">{{ $approvedRequests }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Rejected</p>
            <p class="mt-2 text-3xl font-bold text-red-600">{{ $rejectedRequests }}</p>
        </div>
    </div>

    {{-- Leave Requests --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800">Leave Requests</h2>
            <p class="mt-1 text-sm text-slate-500">
                Review and monitor leave applications.
            </p>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('leaves.index') }}"
            class="grid grid-cols-1 gap-3 border-b border-slate-100 bg-slate-50 p-5 md:grid-cols-3">

            @can('leaves.approve')
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search employee..."
                    class="rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            @endcan

            <select name="leave_type"
                class="rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Leave Types</option>
                <option value="annual" @selected(request('leave_type') === 'annual')>Annual Leave</option>
                <option value="sick" @selected(request('leave_type') === 'sick')>Sick Leave</option>
                <option value="personal" @selected(request('leave_type') === 'personal')>Personal Leave</option>
                <option value="maternity" @selected(request('leave_type') === 'maternity')>Maternity Leave</option>
                <option value="unpaid" @selected(request('leave_type') === 'unpaid')>Unpaid Leave</option>
            </select>

            <div class="flex gap-2">
                <select name="status"
                    class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('leaves.index') }}"
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
                        @can('leaves.approve')
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Employee</th>
                        @endcan
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($leaves as $leave)
                        <tr class="hover:bg-slate-50">
                            @can('leaves.approve')
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="font-medium text-slate-800">
                                        {{ $leave->employee->full_name }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $leave->employee->employee_code }}
                                    </p>
                                </td>
                            @endcan

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ ucwords(str_replace('_', ' ', $leave->leave_type)) }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $leave->start_date->format('d M Y') }}
                                <span class="text-slate-400">to</span>
                                {{ $leave->end_date->format('d M Y') }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $leave->total_days }} days
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($leave->status === 'approved')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Approved</span>
                                @elseif ($leave->status === 'rejected')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Rejected</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Pending</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('leaves.show', $leave) }}"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('leaves.approve') ? 6 : 5 }}"
                                class="px-6 py-12 text-center">
                                <p class="font-medium text-slate-600">No leave requests found</p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Leave requests will appear here.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 p-5">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
