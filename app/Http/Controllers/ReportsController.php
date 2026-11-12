<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportsController extends Controller
{
    /**
     * Display reports.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $reportType = $request->input('report', 'employees');

        abort_unless(
            in_array($reportType, ['employees', 'attendance', 'leaves']),
            404
        );

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $employeeScope = function (Builder $query) use ($user) {
            if ($user->hasRole('Employee')) {
                $employee = $user->employee;
                abort_unless($employee, 403);

                $query->where('id', $employee->id);
            } elseif ($user->hasRole('Manager')) {
                $employee = $user->employee;
                abort_unless($employee, 403);

                $query->where(function ($scope) use ($employee) {
                    $scope->where('id', $employee->id)
                        ->orWhere('manager_id', $employee->id);
                });
            }
        };

        /*
        |--------------------------------------------------------------------------
        | Employee Report
        |--------------------------------------------------------------------------
        */

        $employees = collect();
        $attendanceRecords = collect();
        $leaveRecords = collect();

        if ($reportType === 'employees') {
            $query = Employee::with([
                'department',
                'position',
                'manager',
            ]);

            $employeeScope($query);

            $employees = $query
                ->orderBy('employee_code')
                ->paginate(10)
                ->withQueryString();
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Report
        |--------------------------------------------------------------------------
        */

        if ($reportType === 'attendance') {
            $query = Attendance::with([
                'employee.department',
                'employee.position',
            ]);

            $query->whereHas('employee', function (Builder $employeeQuery) use (
                $employeeScope
            ) {
                $employeeScope($employeeQuery);
            });

            if ($dateFrom) {
                $query->whereDate('attendance_date', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('attendance_date', '<=', $dateTo);
            }

            $attendanceRecords = $query
                ->latest('attendance_date')
                ->paginate(10)
                ->withQueryString();
        }

        /*
        |--------------------------------------------------------------------------
        | Leave Report
        |--------------------------------------------------------------------------
        */

        if ($reportType === 'leaves') {
            $query = Leave::with([
                'employee.department',
                'employee.position',
                'reviewer',
            ]);

            $query->whereHas('employee', function (Builder $employeeQuery) use (
                $employeeScope
            ) {
                $employeeScope($employeeQuery);
            });

            if ($dateFrom) {
                $query->whereDate('start_date', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('end_date', '<=', $dateTo);
            }

            $leaveRecords = $query
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        return view('reports.index', compact(
            'reportType',
            'dateFrom',
            'dateTo',
            'employees',
            'attendanceRecords',
            'leaveRecords'
        ));
    }

    /**
     * Export reports to CSV.
     */
    public function export(Request $request): Response
    {
        $user = $request->user();

        $reportType = $request->input('report', 'employees');

        abort_unless(
            in_array($reportType, ['employees', 'attendance', 'leaves']),
            404
        );

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $employeeScope = function (Builder $query) use ($user) {
            if ($user->hasRole('Employee')) {
                $employee = $user->employee;
                abort_unless($employee, 403);

                $query->where('id', $employee->id);
            } elseif ($user->hasRole('Manager')) {
                $employee = $user->employee;
                abort_unless($employee, 403);

                $query->where(function ($scope) use ($employee) {
                    $scope->where('id', $employee->id)
                        ->orWhere('manager_id', $employee->id);
                });
            }
        };

        $filename = 'peoplehub-' . $reportType . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use (
            $reportType,
            $dateFrom,
            $dateTo,
            $employeeScope
        ) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar CSV terbaca dengan baik di Excel.
            fwrite($file, "\xEF\xBB\xBF");

            if ($reportType === 'employees') {
                fputcsv($file, [
                    'Employee Code',
                    'First Name',
                    'Last Name',
                    'Email',
                    'Phone',
                    'Department',
                    'Position',
                    'Manager',
                    'Hire Date',
                    'Employment Type',
                    'Employment Status',
                ]);

                $query = Employee::with([
                    'department',
                    'position',
                    'manager',
                ]);

                $employeeScope($query);

                $query->orderBy('employee_code')
                    ->chunk(500, function ($employees) use ($file) {
                        foreach ($employees as $employee) {
                            fputcsv($file, [
                                $employee->employee_code,
                                $employee->first_name,
                                $employee->last_name,
                                $employee->email,
                                $employee->phone,
                                $employee->department?->name,
                                $employee->position?->name,
                                $employee->manager?->full_name,
                                $employee->hire_date?->format('Y-m-d'),
                                $employee->employment_type,
                                $employee->employment_status,
                            ]);
                        }
                    });
            }

            if ($reportType === 'attendance') {
                fputcsv($file, [
                    'Employee Code',
                    'Employee Name',
                    'Department',
                    'Position',
                    'Attendance Date',
                    'Check In',
                    'Check Out',
                    'Status',
                    'Notes',
                ]);

                $query = Attendance::with([
                    'employee.department',
                    'employee.position',
                ]);

                $query->whereHas('employee', function (Builder $employeeQuery) use (
                    $employeeScope
                ) {
                    $employeeScope($employeeQuery);
                });

                if ($dateFrom) {
                    $query->whereDate('attendance_date', '>=', $dateFrom);
                }

                if ($dateTo) {
                    $query->whereDate('attendance_date', '<=', $dateTo);
                }

                $query->latest('attendance_date')
                    ->chunk(500, function ($records) use ($file) {
                        foreach ($records as $record) {
                            fputcsv($file, [
                                $record->employee?->employee_code,
                                $record->employee?->full_name,
                                $record->employee?->department?->name,
                                $record->employee?->position?->name,
                                $record->attendance_date?->format('Y-m-d'),
                                $record->check_in?->format('H:i:s'),
                                $record->check_out?->format('H:i:s'),
                                $record->status,
                                $record->notes,
                            ]);
                        }
                    });
            }

            if ($reportType === 'leaves') {
                fputcsv($file, [
                    'Employee Code',
                    'Employee Name',
                    'Department',
                    'Position',
                    'Leave Type',
                    'Start Date',
                    'End Date',
                    'Total Days',
                    'Reason',
                    'Status',
                    'Reviewed By',
                    'Review Notes',
                ]);

                $query = Leave::with([
                    'employee.department',
                    'employee.position',
                    'reviewer',
                ]);

                $query->whereHas('employee', function (Builder $employeeQuery) use (
                    $employeeScope
                ) {
                    $employeeScope($employeeQuery);
                });

                if ($dateFrom) {
                    $query->whereDate('start_date', '>=', $dateFrom);
                }

                if ($dateTo) {
                    $query->whereDate('end_date', '<=', $dateTo);
                }

                $query->latest()
                    ->chunk(500, function ($records) use ($file) {
                        foreach ($records as $record) {
                            fputcsv($file, [
                                $record->employee?->employee_code,
                                $record->employee?->full_name,
                                $record->employee?->department?->name,
                                $record->employee?->position?->name,
                                $record->leave_type,
                                $record->start_date?->format('Y-m-d'),
                                $record->end_date?->format('Y-m-d'),
                                $record->total_days,
                                $record->reason,
                                $record->status,
                                $record->reviewer?->name,
                                $record->review_notes,
                            ]);
                        }
                    });
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
