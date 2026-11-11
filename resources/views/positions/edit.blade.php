
@extends('layouts.peoplehub')

@section('title', 'Edit Position')
@section('page-heading', 'Edit Position')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-xl font-semibold text-slate-800">Edit Position</h2>
        <p class="text-sm text-slate-500 mt-1">
            Update information for {{ $position->name }}.
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

    <form action="{{ route('positions.update', $position) }}" method="POST"
          class="bg-white border border-slate-200 rounded-xl p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Department *
            </label>
            <select name="department_id" required
                    class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        @selected(old('department_id', $position->department_id) == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Position Name *
            </label>
            <input type="text" name="name"
                   value="{{ old('name', $position->name) }}"
                   maxlength="255" required
                   class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Position Level
            </label>
            <input type="text" name="level"
                   value="{{ old('level', $position->level) }}"
                   maxlength="100"
                   placeholder="Example: Junior, Senior, Manager"
                   class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Description
            </label>
            <textarea name="description" rows="4"
                      class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description', $position->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Status *
            </label>
            <select name="is_active" required
                    class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="1"
                    @selected((string) old('is_active', (int) $position->is_active) === '1')>
                    Active
                </option>
                <option value="0"
                    @selected((string) old('is_active', (int) $position->is_active) === '0')>
                    Inactive
                </option>
            </select>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
            <a href="{{ route('positions.index') }}"
               class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Cancel
            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                Update Position
            </button>
        </div>
    </form>
</div>
@endsection
