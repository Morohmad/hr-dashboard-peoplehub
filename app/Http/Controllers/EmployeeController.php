<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display employee directory.
     */
    public function index(Request $request): View
    {
        $baseQuery = Employee::query();

        // Manager hanya melihat dirinya dan bawahannya.
        if ($request->user()->hasRole('Manager')) {
            $managerId = $request->user()->employee?->id;

            $baseQuery->where(function ($query) use ($managerId) {
                if ($managerId) {
                    $query->where('manager_id', $managerId)
                        ->orWhere('id', $managerId);
                } else {
                    $query->whereRaw('1 = 0');
                }
            });
        }

        // Statistik karyawan.
        $totalEmployees = (clone $baseQuery)->count();

        $activeEmployees = (clone $baseQuery)
            ->where('employment_status', 'active')
            ->count();

        $inactiveEmployees = (clone $baseQuery)
            ->where('employment_status', 'inactive')
            ->count();

        $resignedEmployees = (clone $baseQuery)
            ->whereIn('employment_status', ['resigned', 'terminated'])
            ->count();

        // Query daftar karyawan.
        $query = (clone $baseQuery)
            ->with(['department', 'position', 'manager']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('employment_status')) {
            $query->where(
                'employment_status',
                $request->employment_status
            );
        }

        $employees = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('employees.index', compact(
            'employees',
            'departments',
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'resignedEmployees'
        ));
    }

    /**
     * Show create employee form.
     */
    public function create(): View
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $positions = Position::where('is_active', true)
            ->orderBy('name')
            ->get();

        $managers = Employee::active()
            ->orderBy('first_name')
            ->get();

        return view('employees.create', compact(
            'departments',
            'positions',
            'managers'
        ));
    }

    /**
     * Store new employee.
     */
    public function store(
        StoreEmployeeRequest $request
    ): RedirectResponse {
        Employee::create($request->validated());

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display employee details.
     */
    public function show(Employee $employee): View
    {
        $this->authorize('view', $employee);

        $employee->load([
            'department',
            'position',
            'manager',
            'subordinates',
            'user',
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show edit employee form.
     */
    public function edit(Employee $employee): View
    {
        $this->authorize('update', $employee);

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $positions = Position::where('is_active', true)
            ->orderBy('name')
            ->get();

        $managers = Employee::active()
            ->where('id', '!=', $employee->id)
            ->orderBy('first_name')
            ->get();

        return view('employees.edit', compact(
            'employee',
            'departments',
            'positions',
            'managers'
        ));
    }

    /**
     * Update employee.
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    ): RedirectResponse {
        $employee->update($request->validated());

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Soft delete employee.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}