<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PositionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Position::query()
            ->with('department')
            ->withCount('employees');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($departmentQuery) use ($search) {
                        $departmentQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $totalPositions = Position::count();
        $activePositions = Position::where('is_active', true)->count();
        $inactivePositions = Position::where('is_active', false)->count();

        $positions = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('positions.index', compact(
            'positions',
            'departments',
            'totalPositions',
            'activePositions',
            'inactivePositions'
        ));
    }

    public function create(): View
    {
        abort_unless(
            request()->user()->can('positions.create'),
            403
        );

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('positions.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()->can('positions.create'),
            403
        );

        $validated = $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('positions', 'name')
                    ->where('department_id', $request->department_id),
            ],
            'level' => [
                'nullable',
                'string',
                'max:100',
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

        Position::create($validated);

        return redirect()
            ->route('positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function show(Position $position): View
    {
        $position->load('department');

        $position->loadCount('employees');

        $employees = Employee::with(['department', 'manager'])
            ->where('position_id', $position->id)
            ->orderBy('first_name')
            ->paginate(10);

        return view('positions.show', compact(
            'position',
            'employees'
        ));
    }

    public function edit(Position $position): View
    {
        abort_unless(
            request()->user()->can('positions.edit'),
            403
        );

        $departments = Department::where('is_active', true)
            ->orWhere('id', $position->department_id)
            ->orderBy('name')
            ->get();

        return view('positions.edit', compact(
            'position',
            'departments'
        ));
    }

    public function update(
        Request $request,
        Position $position
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('positions.edit'),
            403
        );

        $validated = $request->validate([
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('positions', 'name')
                    ->where('department_id', $request->department_id)
                    ->ignore($position->id),
            ],
            'level' => [
                'nullable',
                'string',
                'max:100',
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

        $position->update($validated);

        return redirect()
            ->route('positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        abort_unless(
            request()->user()->can('positions.delete'),
            403
        );

        if ($position->employees()->exists()) {
            return redirect()
                ->route('positions.index')
                ->with(
                    'error',
                    'Position cannot be deleted because it is still assigned to employees.'
                );
        }

        $position->delete();

        return redirect()
            ->route('positions.index')
            ->with('success', 'Position deleted successfully.');
    }
}
