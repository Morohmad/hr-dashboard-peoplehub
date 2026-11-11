
@extends('layouts.peoplehub')

@section('title', 'Leave Details')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    {{-- Page Header --}}
    <div>
        <a href="{{ route('leaves.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
            &larr; Back to Leave Management
        </a>

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Leave Request Details</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Review leave request information.
                </p>
            </div>

            {{-- Status Badge --}}
            @if ($leave->status === 'approved')
                <span class="w-fit rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                    Approved
                </span>
            @elseif ($leave->status === 'rejected')
                <span class="w-fit rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">
                    Rejected
                </span>
            @else
                <span class="w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                    Pending Review
                </span>
            @endif
        </div>
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

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Please correct the following errors:</p>

            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Employee Information --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800">Employee Information</h2>
            <p class="mt-1 text-sm text-slate-500">
                Information about the employee submitting this request.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Employee Name
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $leave->employee->full_name }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Employee Code
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $leave->employee->employee_code }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Department
                </p>
                <p class="mt-1 text-sm text-slate-700">
                    {{ $leave->employee->department?->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Position
                </p>
                <p class="mt-1 text-sm text-slate-700">
                    {{ $leave->employee->position?->name ?? '-' }}
                </p>
            </div>

        </div>
    </div>

    {{-- Leave Information --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800">Leave Information</h2>
            <p class="mt-1 text-sm text-slate-500">
                Details of the requested leave period.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Leave Type
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ ucwords(str_replace('_', ' ', $leave->leave_type)) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Total Days
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $leave->total_days }} days
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Start Date
                </p>
                <p class="mt-1 text-sm text-slate-700">
                    {{ $leave->start_date->format('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    End Date
                </p>
                <p class="mt-1 text-sm text-slate-700">
                    {{ $leave->end_date->format('d F Y') }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Reason
                </p>
                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">
                    {{ $leave->reason }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                    Submitted At
                </p>
                <p class="mt-1 text-sm text-slate-700">
                    {{ $leave->created_at->format('d F Y, H:i') }}
                </p>
            </div>

        </div>
    </div>

    {{-- Review Information --}}
    @if ($leave->reviewed_at)
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5">
                <h2 class="font-semibold text-slate-800">Review Information</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Information about the review decision.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Reviewed By
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $leave->reviewer?->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Reviewed At
                    </p>
                    <p class="mt-1 text-sm text-slate-700">
                        {{ $leave->reviewed_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Review Notes
                    </p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">
                        {{ $leave->review_notes ?: 'No review notes provided.' }}
                    </p>
                </div>

            </div>
        </div>
    @endif

    {{-- Review Actions --}}
    @if (auth()->user()->can('leaves.approve') && $leave->status === 'pending')

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <h2 class="font-semibold text-slate-800">Review This Request</h2>
            <p class="mt-1 text-sm text-slate-500">
                Approve or reject this employee leave request.
            </p>

            {{-- Approve Form --}}
            <form method="POST"
                action="{{ route('leaves.approve', $leave) }}"
                class="mt-5 space-y-3"
                onsubmit="return confirm('Are you sure you want to approve this leave request?')">

                @csrf

                <div>
                    <label for="approve_notes"
                        class="mb-2 block text-sm font-medium text-slate-700">
                        Approval Notes (Optional)
                    </label>

                    <textarea
                        id="approve_notes"
                        name="review_notes"
                        rows="3"
                        maxlength="1000"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Add an approval note...">{{ old('review_notes') }}</textarea>
                </div>

                <button type="submit"
                    class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                    Approve Request
                </button>

            </form>

            <div class="my-6 border-t border-slate-100"></div>

            {{-- Reject Form --}}
            <form method="POST"
                action="{{ route('leaves.reject', $leave) }}"
                class="space-y-3"
                onsubmit="return confirm('Are you sure you want to reject this leave request?')">

                @csrf

                <div>
                    <label for="reject_notes"
                        class="mb-2 block text-sm font-medium text-slate-700">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        id="reject_notes"
                        name="review_notes"
                        rows="3"
                        required
                        minlength="5"
                        maxlength="1000"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Explain why this request is rejected...">{{ old('review_notes') }}</textarea>
                </div>

                <button type="submit"
                    class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                    Reject Request
                </button>

            </form>

        </div>

    @endif

    {{-- Employee Cancel Action --}}
    @if (
        auth()->user()->can('leaves.create')
        && auth()->user()->employee?->id === $leave->employee_id
        && $leave->status === 'pending'
    )

        <div class="flex justify-end">

            <form method="POST"
                action="{{ route('leaves.destroy', $leave) }}"
                onsubmit="return confirm('Are you sure you want to cancel this leave request?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                    class="rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    Cancel Request
                </button>

            </form>

        </div>

    @endif

</div>
@endsection
