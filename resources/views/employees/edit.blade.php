
@extends('layouts.peoplehub')

@section('title', 'Edit Employee')
@section('page-heading', 'Edit Employee')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <h2 class="text-xl font-semibold text-slate-800">Edit Employee Information</h2>
        <p class="text-sm text-slate-500 mt-1">
            Update information for {{ $employee->full_name }}.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            <p class="font-semibold mb-2">Please correct the following errors:</p>
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.update', $employee) }}" method="POST"
          class="bg-white rounded-xl border border-slate-200 p-6 space-y-8">
        @csrf
        @method('PUT')

        <section>
            <h3 class="font-semibold text-slate-800 mb-4">Personal Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">First Name *</label>
                    <input type="text" name="first_name"
                           value="{{ old('first_name', $employee->first_name) }}" required
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Last Name</label>
                    <input type="text" name="last_name"
                           value="{{ old('last_name', $employee->last_name) }}"
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" name="email"
                           value="{{ old('email', $employee->email) }}" required
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', $employee->phone) }}"
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Birth Date</label>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date', optional($employee->birth_date)->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Gender</label>
                    <select name="gender" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Gender</option>
                        <option value="male" @selected(old('gender', $employee->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $employee->gender) === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $employee->gender) === 'other')>Other</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <textarea name="address" rows="3"
                              class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">{{ old('address', $employee->address) }}</textarea>
                </div>
            </div>
        </section>

        <div class="border-t border-slate-200"></div>

        <section>
            <h3 class="font-semibold text-slate-800 mb-4">Employment Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Employee Code *</label>
                    <input type="text" name="employee_code"
                           value="{{ old('employee_code', $employee->employee_code) }}" required
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Hire Date *</label>
                    <input type="date" name="hire_date"
                           value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}" required
                           class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Department *</label>
                    <select name="department_id" id="department_id" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                @selected(old('department_id', $employee->department_id) == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Position *</label>
                    <select name="position_id" id="position_id" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Position</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}"
                                    data-department="{{ $position->department_id }}"
                                @selected(old('position_id', $employee->position_id) == $position->id)>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Manager</label>
                    <select name="manager_id"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">No Manager</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}"
                                @selected(old('manager_id', $employee->manager_id) == $manager->id)>
                                {{ $manager->full_name }} ({{ $manager->employee_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Employment Type *</label>
                    <select name="employment_type" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach (['permanent' => 'Permanent', 'contract' => 'Contract', 'internship' => 'Internship', 'probation' => 'Probation'] as $value => $label)
                            <option value="{{ $value }}"
                                @selected(old('employment_type', $employee->employment_type) === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Employment Status *</label>
                    <select name="employment_status" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'resigned' => 'Resigned', 'terminated' => 'Terminated'] as $value => $label)
                            <option value="{{ $value }}"
                                @selected(old('employment_status', $employee->employment_status) === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">
            <a href="{{ route('employees.index') }}"
               class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                Cancel
            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                Update Employee
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department_id');
    const positionSelect = document.getElementById('position_id');

    function filterPositions() {
        const departmentId = departmentSelect.value;

        Array.from(positionSelect.options).forEach(function (option) {
            if (!option.value) return;

            const matches = option.dataset.department === departmentId;
            option.hidden = !matches;
            option.disabled = !matches;
        });

        if (positionSelect.selectedOptions[0]?.disabled) {
            positionSelect.value = '';
        }
    }

    departmentSelect.addEventListener('change', filterPositions);
    filterPositions();
});
</script>
@endsection
