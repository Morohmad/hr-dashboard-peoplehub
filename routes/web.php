<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $totalEmployees = Employee::count();

        $activeEmployees = Employee::where(
            'employment_status',
            'active'
        )->count();

        return view('dashboard', compact(
            'totalEmployees',
            'activeEmployees'
        ));
    })->name('dashboard');

    // Employee Management
    Route::resource('employees', EmployeeController::class)
        ->middleware('permission:employees.view');

    // Department Management
    Route::resource('departments', DepartmentController::class)
        ->middleware('permission:departments.view');

    // Position Management
    Route::resource('positions', PositionController::class)
        ->middleware('permission:positions.view');
    
    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->middleware('permission:attendance.view')
        ->name('attendance.index');

    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->middleware('permission:attendance.view')
        ->name('attendance.check-in');

    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->middleware('permission:attendance.view')
        ->name('attendance.check-out');

    // Payroll placeholder
    Route::middleware('permission:payroll.view')->group(function () {
        Route::get('/payroll', function () {
            return 'Payroll Management';
        })->name('payroll.index');
    });
});

require __DIR__.'/auth.php';