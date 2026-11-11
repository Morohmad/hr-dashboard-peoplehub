
@extends('layouts.peoplehub')

@section('title', 'Request Leave')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <a href="{{ route('leaves.index') }}"
            class="text-sm font-medium text-blue-600 hover:text-blue-800">
            &larr; Back to Leave Management
        </a>
        <h1 class="mt-3 text-2xl font-bold text-slate-800">Request Leave</h1>
        <p class="mt-1 text-sm text-slate-500">
            Complete the form below to submit your leave request.
        </p>
    </div>

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

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('leaves.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="leave_type" class="mb-2 block text-sm font-medium text-slate-700">
                    Leave Type <span class="text-red-500">*</span>
                </label>
                <select id="leave_type" name="leave_type" required
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select leave type</option>
                    <option value="annual" @selected(old('leave_type') === 'annual')>Annual Leave</option>
                    <option value="sick" @selected(old('leave_type') === 'sick')>Sick Leave</option>
                    <option value="personal" @selected(old('leave_type') === 'personal')>Personal Leave</option>
                    <option value="maternity" @selected(old('leave_type') === 'maternity')>Maternity Leave</option>
                    <option value="unpaid" @selected(old('leave_type') === 'unpaid')>Unpaid Leave</option>
                </select>
                @error('leave_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="start_date" class="mb-2 block text-sm font-medium text-slate-700">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ old('start_date') }}" min="{{ now()->toDateString() }}" required
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="mb-2 block text-sm font-medium text-slate-700">
                        End Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="end_date" name="end_date"
                        value="{{ old('end_date') }}" min="{{ now()->toDateString() }}" required
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="reason" class="mb-2 block text-sm font-medium text-slate-700">
                    Reason <span class="text-red-500">*</span>
                </label>
                <textarea id="reason" name="reason" rows="5" required minlength="10" maxlength="1000"
                    placeholder="Explain the reason for your leave request..."
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('reason') }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Minimum 10 characters, maximum 1000 characters.</p>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-lg bg-blue-50 p-4 text-sm text-blue-700">
                Your request will be submitted with Pending status and must be reviewed by HR or your manager.
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('leaves.index') }}"
                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>

                <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
