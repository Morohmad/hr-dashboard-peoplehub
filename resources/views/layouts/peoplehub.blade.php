<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - PeopleHub</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen">

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-64
                      border-r border-slate-200 bg-white lg:block">

            <div class="flex h-16 items-center gap-3 border-b
                        border-slate-200 px-6">

                <div class="flex h-9 w-9 items-center justify-center
                            rounded-xl bg-blue-600 text-white font-bold">
                    P
                </div>

                <span class="text-xl font-bold tracking-tight">
                    PeopleHub
                </span>

            </div>

            <nav class="space-y-1 px-4 py-6">

                <p class="mb-3 px-3 text-xs font-semibold uppercase
                        tracking-wider text-slate-400">
                    Main Menu
                </p>

                @can('dashboard.view')
                    <a href="{{ route('dashboard') }}"
                    class="block rounded-lg bg-blue-50 px-3 py-2.5
                            text-sm font-semibold text-blue-600">
                        Dashboard
                    </a>
                @endcan

                @can('employees.view')
                    <a href="{{ route('employees.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm
                            text-slate-600 hover:bg-slate-100">
                        Employees
                    </a>
                @endcan

                @can('departments.view')
                    <a href="{{ route('departments.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm
                            text-slate-600 hover:bg-slate-100">
                        Departments
                    </a>
                @endcan

                @can('attendance.view')
                    <a href="#"
                    class="block rounded-lg px-3 py-2.5 text-sm
                            text-slate-600 hover:bg-slate-100">
                        Attendance
                    </a>
                @endcan

                @can('leaves.view')
                    <a href="#"
                    class="block rounded-lg px-3 py-2.5 text-sm
                            text-slate-600 hover:bg-slate-100">
                        Leave Management
                    </a>
                @endcan

                @can('payroll.view')
                    <a href="{{ route('payroll.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm
                            text-slate-600 hover:bg-slate-100">
                        Payroll
                    </a>
                @endcan

                @can('reports.view')
                    <a href="#"
                    class="block rounded-lg px-3 py-2.5 text-sm
                            text-slate-600 hover:bg-slate-100">
                        Reports
                    </a>
                @endcan

            </nav>

        </aside>

        {{-- Main Content --}}
        <div class="lg:pl-64">

            {{-- Navbar --}}
            <header class="sticky top-0 z-30 flex h-16 items-center
                           justify-between border-b border-slate-200
                           bg-white px-6">

                <div>
                    <h2 class="font-semibold">
                        @yield('page-heading', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-4">

                    <div class="text-right">
                        <p class="text-sm font-semibold">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="rounded-lg bg-slate-100
                                       px-3 py-2 text-sm font-medium
                                       hover:bg-slate-200">
                            Logout
                        </button>
                    </form>

                </div>

            </header>

            {{-- Page Content --}}
            <main class="p-6 lg:p-8">

                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>