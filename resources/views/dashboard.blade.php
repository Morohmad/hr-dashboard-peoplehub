
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
                    'value' => number_format($totalEmployees),
                    'description' => 'Registered employees',
                    'color' => 'indigo',
                ],
                [
                    'title' => 'Active Employees',
                    'value' => number_format($activeEmployees),
                    'description' => 'Currently working',
                    'color' => 'green',
                ],
                [
                    'title' => 'On Leave',
                    'value' => '0',
                    'description' => 'Approved leave requests',
                    'color' => 'amber',
                ],
                [
                    'title' => 'Pending Requests',
                    'value' => '0',
                    'description' => 'Awaiting approval',
                    'color' => 'red',
                ],
            ];
        @endphp

        @foreach ($statistics as $stat)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        {{ $stat['title'] }}
                    </p>

                    @if ($stat['color'] === 'indigo')
                        <div class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    @elseif ($stat['color'] === 'green')
                        <div class="rounded-lg bg-green-50 p-2 text-green-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @elseif ($stat['color'] === 'amber')
                        <div class="rounded-lg bg-amber-50 p-2 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                            </svg>
                        </div>
                    @else
                        <div class="rounded-lg bg-red-50 p-2 text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <h3 class="mt-4 text-3xl font-bold text-slate-900">
                    {{ $stat['value'] }}
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $stat['description'] }}
                </p>

            </div>
        @endforeach

    </div>

    {{-- Welcome Card --}}
    <div class="mt-8 rounded-xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-900">
            Your HR workspace is ready
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Start managing your employees, attendance,
            leave requests, and payroll with PeopleHub.
        </p>

        <div class="mt-5">
            <a href="{{ route('employees.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                Manage Employees
                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>

@endsection
