@extends('layouts.peoplehub')

@section('title', 'Dashboard')

@section('page-heading', 'Overview')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
            Welcome to PeopleHub
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Manage your workforce from one central platform.
        </p>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        @php
            $statistics = [
                [
                    'title' => 'Total Employees',
                    'value' => '0',
                    'description' => 'Registered employees',
                ],
                [
                    'title' => 'Present Today',
                    'value' => '0',
                    'description' => 'Employees checked in',
                ],
                [
                    'title' => 'On Leave',
                    'value' => '0',
                    'description' => 'Approved leave requests',
                ],
                [
                    'title' => 'Pending Requests',
                    'value' => '0',
                    'description' => 'Awaiting approval',
                ],
            ];
        @endphp

        @foreach ($statistics as $stat)
            <div class="rounded-xl border border-slate-200
                        bg-white p-5 shadow-sm">

                <p class="text-sm font-medium text-slate-500">
                    {{ $stat['title'] }}
                </p>

                <h3 class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $stat['value'] }}
                </h3>

                <p class="mt-2 text-xs text-slate-400">
                    {{ $stat['description'] }}
                </p>

            </div>
        @endforeach

    </div>

    {{-- Welcome Card --}}
    <div class="mt-8 rounded-xl border border-slate-200
                bg-white p-8 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Your HR workspace is ready
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Start managing your employees, attendance,
            leave requests, and payroll with PeopleHub.
        </p>

    </div>

@endsection