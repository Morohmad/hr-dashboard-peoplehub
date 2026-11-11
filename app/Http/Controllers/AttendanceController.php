<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display attendance records.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Attendance::with([
            'employee.department',
            'employee.position',
        ]);

        // HR Admin and users with attendance.manage can see all records.
        if (!$user->can('attendance.manage')) {
            $employee = $user->employee;

            abort_unless($employee, 403);

            $query->where('employee_id', $employee->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('employee', function ($employeeQuery) use ($search) {
                $employeeQuery->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest('attendance_date')
            ->paginate(10)
            ->withQueryString();

        $today = Carbon::today();

        $todayQuery = Attendance::whereDate('attendance_date', $today);

        if (!$user->can('attendance.manage')) {
            $todayQuery->where('employee_id', $user->employee->id);
        }

        $todayAttendance = (clone $todayQuery)->first();

        $presentCount = (clone $todayQuery)
            ->where('status', 'present')
            ->count();

        $lateCount = (clone $todayQuery)
            ->where('status', 'late')
            ->count();

        return view('attendances.index', compact(
            'attendances',
            'todayAttendance',
            'presentCount',
            'lateCount'
        ));
    }

    /**
     * Employee check-in.
     */
    public function checkIn(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;

        abort_unless($employee, 403, 'Your account is not linked to an employee profile.');

        $now = Carbon::now(config('app.timezone'));
        $today = $now->toDateString();

        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'attendance_date' => $today,
            ],
            [
                'check_in' => null,
                'status' => 'present',
            ]
        );

        if ($attendance->check_in !== null) {
            return back()->with(
                'error',
                'You have already checked in today.'
            );
        }

        $attendance->update([
            'check_in' => $now,
            'status' => $now->format('H:i:s') > '08:00:00'
                ? 'late'
                : 'present',
        ]);

        return back()->with(
            'success',
            'Check-in recorded successfully at ' . $now->format('H:i:s')
        );
    }

    /**
     * Employee check-out.
     */
    public function checkOut(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;

        abort_unless($employee, 403, 'Your account is not linked to an employee profile.');

        $today = Carbon::today(config('app.timezone'));

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with(
                'error',
                'You must check in before checking out.'
            );
        }

        if ($attendance->check_out !== null) {
            return back()->with(
                'error',
                'You have already checked out today.'
            );
        }

        $now = Carbon::now(config('app.timezone'));

        $attendance->update([
            'check_out' => $now,
        ]);

        return back()->with(
            'success',
            'Check-out recorded successfully at ' . $now->format('H:i:s')
        );
    }
}
