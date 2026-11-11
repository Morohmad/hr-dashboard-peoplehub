<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Employee statistics
        $employeeQuery = Employee::query();

        // Employee hanya melihat statistik miliknya sendiri
        if ($user->hasRole('Employee')) {
            $employeeQuery->where('user_id', $user->id);
        }

        $totalEmployees = (clone $employeeQuery)->count();

        $activeEmployees = (clone $employeeQuery)
            ->where('employment_status', 'active')
            ->count();

        // Organization statistics
        $totalDepartments = Department::count();
        $totalPositions = Position::count();

        // Attendance statistics
        $attendanceQuery = Attendance::whereDate(
            'attendance_date',
            today()
        );

        if ($user->hasRole('Employee')) {
            $attendanceQuery->whereHas('employee', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $presentToday = (clone $attendanceQuery)
            ->where('status', 'present')
            ->count();

        $lateToday = (clone $attendanceQuery)
            ->where('status', 'late')
            ->count();

        $absentToday = (clone $attendanceQuery)
            ->where('status', 'absent')
            ->count();

        // Leave statistics
        $leaveQuery = Leave::query();

        if (!$user->can('leaves.approve')) {
            $leaveQuery->where('employee_id', $user->employee?->id);
        }

        $pendingLeaves = (clone $leaveQuery)
            ->where('status', 'pending')
            ->count();

        $approvedLeaves = (clone $leaveQuery)
            ->where('status', 'approved')
            ->count();

        $rejectedLeaves = (clone $leaveQuery)
            ->where('status', 'rejected')
            ->count();

        // Employee by department
        $employeesByDepartment = Department::withCount([
            'employees' => function ($query) {
                $query->where('employment_status', 'active');
            }
        ])
            ->orderBy('name')
            ->get();

        // Recent leave requests
        $recentLeavesQuery = Leave::with('employee.department')
            ->latest()
            ->limit(5);

        if (!$user->can('leaves.approve')) {
            $recentLeavesQuery->where(
                'employee_id',
                $user->employee?->id
            );
        }

        $recentLeaves = $recentLeavesQuery->get();

        // Recent attendance
        $recentAttendanceQuery = Attendance::with('employee.department')
            ->whereDate('attendance_date', today())
            ->latest('check_in')
            ->limit(5);

        if ($user->hasRole('Employee')) {
            $recentAttendanceQuery->whereHas('employee', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $recentAttendances = $recentAttendanceQuery->get();

        return view('dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'totalDepartments',
            'totalPositions',
            'presentToday',
            'lateToday',
            'absentToday',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves',
            'employeesByDepartment',
            'recentLeaves',
            'recentAttendances'
        ));
    }
}
