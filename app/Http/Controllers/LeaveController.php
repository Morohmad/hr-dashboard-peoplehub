<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    /**
     * Display leave requests.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Leave::with(['employee.department', 'employee.position', 'reviewer']);

        // Employee hanya melihat pengajuan cuti miliknya.
        if (!$user->can('leaves.approve')) {
            $employee = $user->employee;
            abort_unless($employee, 403);

            $query->where('employee_id', $employee->id);
        }

        if ($request->filled('search') && $user->can('leaves.approve')) {
            $search = $request->search;

            $query->whereHas('employee', function ($employeeQuery) use ($search) {
                $employeeQuery->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistik sesuai cakupan data pengguna.
        $statsQuery = Leave::query();

        if (!$user->can('leaves.approve')) {
            $statsQuery->where('employee_id', $user->employee->id);
        }

        $totalRequests = (clone $statsQuery)->count();
        $pendingRequests = (clone $statsQuery)->where('status', 'pending')->count();
        $approvedRequests = (clone $statsQuery)->where('status', 'approved')->count();
        $rejectedRequests = (clone $statsQuery)->where('status', 'rejected')->count();

        return view('leaves.index', compact(
            'leaves',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests'
        ));
    }

    /**
     * Show leave request form.
     */
    public function create(Request $request): View
    {
        abort_unless($request->user()->can('leaves.create'), 403);

        abort_unless(
            $request->user()->employee,
            403,
            'Your account is not linked to an employee profile.'
        );

        return view('leaves.create');
    }

    /**
     * Store a new leave request.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('leaves.create'), 403);

        $employee = $request->user()->employee;

        abort_unless(
            $employee,
            403,
            'Your account is not linked to an employee profile.'
        );

        $validated = $request->validate([
            'leave_type' => [
                'required',
                Rule::in(['annual', 'sick', 'personal', 'maternity', 'unpaid']),
            ],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $totalDays = $startDate->diffInDays($endDate) + 1;

        Leave::create([
            'employee_id' => $employee->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Catat aktivitas pengajuan cuti.
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'employee_id' => $employee->id,
            'action' => 'leave_submitted',
            'description' => $employee->full_name . ' mengajukan cuti ' . $validated['leave_type'] . '.',
        ]);

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display leave request details.
     */
    public function show(Request $request, Leave $leave): View
    {
        $user = $request->user();

        if (!$user->can('leaves.approve')) {
            abort_unless(
                $user->employee && $leave->employee_id === $user->employee->id,
                403
            );
        }

        $leave->load(['employee.department', 'employee.position', 'reviewer']);

        return view('leaves.show', compact('leave'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, Leave $leave): RedirectResponse
    {
        abort_unless($request->user()->can('leaves.approve'), 403);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been reviewed.');
        }

        $validated = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $leave->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        // Catat aktivitas persetujuan cuti.
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'employee_id' => $leave->employee_id,
            'action' => 'leave_approved',
            'description' => $request->user()->name
                . ' menyetujui pengajuan cuti '
                . $leave->employee->full_name . '.',
        ]);

        return back()->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, Leave $leave): RedirectResponse
    {
        abort_unless($request->user()->can('leaves.approve'), 403);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been reviewed.');
        }

        $validated = $request->validate([
            'review_notes' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $leave->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'],
        ]);

        // Catat aktivitas penolakan cuti.
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'employee_id' => $leave->employee_id,
            'action' => 'leave_rejected',
            'description' => $request->user()->name
                . ' menolak pengajuan cuti '
                . $leave->employee->full_name . '.',
        ]);

        return back()->with('success', 'Leave request rejected successfully.');
    }

    /**
     * Cancel a pending leave request.
     */
    public function destroy(Request $request, Leave $leave): RedirectResponse
    {
        abort_unless($request->user()->can('leaves.create'), 403);

        $employee = $request->user()->employee;

        abort_unless(
            $employee && $leave->employee_id === $employee->id,
            403
        );

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $leave->delete();

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request cancelled successfully.');
    }
}
