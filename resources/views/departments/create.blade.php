
@extends('layouts.peoplehub')

@section('title', 'Add Department')
@section('page-heading', 'Add Department')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-xl font-semibold text-slate-800">Create New Department</h2>
        <p class="text-sm text-slate-500 mt-1">
            Add a new department to your organization.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('departments.store') }}" method="POST"
          class="bg-white border border-slate-200 rounded-xl p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Department Name *
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Example: Information Technology"
                   class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Department Code *
            </label>
            <input type="text" name="code" value="{{ old('code') }}" required
                   maxlength="20" placeholder="Example: IT"
                   class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
            <p class="text-xs text-slate-500 mt-1">Use a unique short code for each department.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Description
            </label>
            <textarea name="description" rows="4"
                      placeholder="Describe the department's responsibilities..."
                      class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Status *
            </label>
            <select name="is_active" required
                    class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="1" @selected(old('is_active', '1') == '1')>Active</option>
                <option value="0" @selected(old('is_active') === '0')>Inactive</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
            <a href="{{ route('departments.index') }}"
               class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Cancel
            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                Save Department
            </button>
        </div>
    </form>
</div>
@endsection
