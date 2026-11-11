<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $isEmployee = $user->hasRole('Employee');
        $isManager = $user->hasRole('Manager');

        $employee = $user->employee;
        $employeeId = $employee?->id;

        /*
        |--------------------------------------------------------------------------
        | Employee Data Scope
        |--------------------------------------------------------------------------
        | Super Admin & HR Admin : seluruh karyawan
        | Manager                 : dirinya sendiri dan anggota tim langsung
        | Employee                : dirinya sendiri
        */

        $applyEmployeeScope = function (Builder $query) use (
            $isEmployee,
            $isManager,
            $employeeId,
            $user
        ) {
            if ($isEmployee) {
                if ($employeeId) {
                    $query->where('id', $employeeId);
                } else {
                    $query->whereRaw('1 = 0');
                }
            } elseif ($isManager) {
                if ($employeeId) {
                    $query->where(function ($subQuery) use ($employeeId) {
                        $subQuery->where('id', $employeeId)
                            ->orWhere('manager_id', $employeeId);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        };

        $scopedEmployees = Employee::query();
        $applyEmployeeScope($scopedEmployees);

        /*
        |--------------------------------------------------------------------------
        | Employee Statistics
        |--------------------------------------------------------------------------
        */

        $totalEmployees = (clone $scopedEmployees)->count();

        $activeEmployees = (clone $scopedEmployees)
            ->where('employment_status', 'active')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Organization Statistics
        |--------------------------------------------------------------------------
        */

        if ($isEmployee || $isManager) {
            $totalDepartments = (clone $scopedEmployees)
                ->whereNotNull('department_id')
                ->distinct()
                ->count('department_id');

            $totalPositions = (clone $scopedEmployees)
                ->whereNotNull('position_id')
                ->distinct()
                ->count('position_id');
        } else {
            $totalDepartments = Department::count();
            $totalPositions = Position::count();
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Statistics
        |--------------------------------------------------------------------------
        */

        $attendanceQuery = Attendance::whereDate(
            'attendance_date',
            today()
        );

        $attendanceQuery->whereHas('employee', function (Builder $query) use (
            $applyEmployeeScope
        ) {
            $applyEmployeeScope($query);
        });

        $presentToday = (clone $attendanceQuery)
            ->where('status', 'present')
            ->count();

        $lateToday = (clone $attendanceQuery)
            ->where('status', 'late')
            ->count();

        $absentToday = (clone $attendanceQuery)
            ->where('status', 'absent')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Leave Statistics
        |--------------------------------------------------------------------------
        */

        $leaveQuery = Leave::whereHas('employee', function (Builder $query) use (
            $applyEmployeeScope
        ) {
            $applyEmployeeScope($query);
        });

        $pendingLeaves = (clone $leaveQuery)
            ->where('status', 'pending')
            ->count();

        $approvedLeaves = (clone $leaveQuery)
            ->where('status', 'approved')
            ->count();

        $rejectedLeaves = (clone $leaveQuery)
            ->where('status', 'rejected')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Employees by Department
        |--------------------------------------------------------------------------
        */

        $employeesByDepartment = Department::withCount([
            'employees' => function (Builder $query) use (
                $applyEmployeeScope
            ) {
                $query->where('employment_status', 'active');
                $applyEmployeeScope($query);
            }
        ])
            ->when(
                $isEmployee || $isManager,
                function (Builder $query) {
                    $query->having('employees_count', '>', 0);
                }
            )
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Leave Requests
        |--------------------------------------------------------------------------
        */

        $recentLeavesQuery = Leave::with([
            'employee.department',
            'employee.position',
        ])
            ->whereHas('employee', function (Builder $query) use (
                $applyEmployeeScope
            ) {
                $applyEmployeeScope($query);
            })
            ->latest()
            ->limit(5);

        $recentLeaves = $recentLeavesQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Attendance
        |--------------------------------------------------------------------------
        */

        $recentAttendanceQuery = Attendance::with([
            'employee.department',
            'employee.position',
        ])
            ->whereDate('attendance_date', today())
            ->whereHas('employee', function (Builder $query) use (
                $applyEmployeeScope
            ) {
                $applyEmployeeScope($query);
            })
            ->latest('check_in')
            ->limit(5);

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
