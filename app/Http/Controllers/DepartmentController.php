<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request): View
    {
        $query = Department::query()
            ->withCount(['employees', 'positions']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        $totalDepartments = Department::count();

        $activeDepartments = Department::where(
            'is_active',
            true
        )->count();

        $inactiveDepartments = Department::where(
            'is_active',
            false
        )->count();

        $departments = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact(
            'departments',
            'totalDepartments',
            'activeDepartments',
            'inactiveDepartments'
        ));
    }

    /**
     * Show the form for creating a department.
     */
    public function create(): View
    {
        abort_unless(
            request()->user()->can('departments.create'),
            403
        );

        return view('departments.create');
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()->can('departments.create'),
            403
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:departments,name',
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                'unique:departments,code',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department): View
    {
        $department->loadCount([
            'employees',
            'positions',
        ]);

        $department->load([
            'employees' => function ($query) {
                $query->with('position')
                    ->orderBy('first_name');
            },
            'positions',
        ]);

        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing a department.
     */
    public function edit(Department $department): View
    {
        abort_unless(
            request()->user()->can('departments.edit'),
            403
        );

        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(
        Request $request,
        Department $department
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('departments.edit'),
            403
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')
                    ->ignore($department->id),
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('departments', 'code')
                    ->ignore($department->id),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Delete the specified department.
     */
    public function destroy(Department $department): RedirectResponse
    {
        abort_unless(
            request()->user()->can('departments.delete'),
            403
        );

        // Prevent deletion if department still has employees or positions.
        if (
            $department->employees()->exists() ||
            $department->positions()->exists()
        ) {
            return redirect()
                ->route('departments.index')
                ->with(
                    'error',
                    'Department cannot be deleted because it still has employees or positions.'
                );
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
