
@extends('layouts.peoplehub')

@section('title', 'Departments')
@section('page-heading', 'Department Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Departments</h2>
            <p class="text-sm text-slate-500 mt-1">
                Manage your organization's departments and structure.
            </p>
        </div>

        @can('departments.create')
            <a href="{{ route('departments.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                <span class="mr-2">+</span> Add Department
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Total Departments</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalDepartments }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Active Departments</p>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $activeDepartments }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <p class="text-sm text-slate-500">Inactive Departments</p>
            <p class="text-2xl font-bold text-slate-500 mt-2">{{ $inactiveDepartments }}</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <form method="GET" action="{{ route('departments.index') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Search department name or code..."
                   class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">

            <select name="status"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Search
                </button>

                <a href="{{ route('departments.index') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Employees</th>
                        <th class="px-6 py-4">Positions</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($departments as $department)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $department->name }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $department->description ?: 'No description' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ $department->code }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $department->employees_count }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $department->positions_count }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($department->is_active)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('departments.show', $department) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        View
                                    </a>

                                    @can('departments.edit')
                                        <a href="{{ route('departments.edit', $department) }}"
                                           class="text-amber-600 hover:text-amber-800 font-medium">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('departments.delete')
                                        <form action="{{ route('departments.destroy', $department) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this department?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                No departments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection
