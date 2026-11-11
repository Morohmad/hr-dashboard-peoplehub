<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

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

    // Leave Management
    Route::prefix('leaves')
        ->name('leaves.')
        ->group(function () {

            Route::get('/', [LeaveController::class, 'index'])
                ->middleware('permission:leaves.view')
                ->name('index');

            Route::get('/create', [LeaveController::class, 'create'])
                ->middleware('permission:leaves.create')
                ->name('create');

            Route::post('/', [LeaveController::class, 'store'])
                ->middleware('permission:leaves.create')
                ->name('store');

            Route::get('/{leave}', [LeaveController::class, 'show'])
                ->middleware('permission:leaves.view')
                ->name('show');

            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])
                ->middleware('permission:leaves.approve')
                ->name('approve');

            Route::post('/{leave}/reject', [LeaveController::class, 'reject'])
                ->middleware('permission:leaves.approve')
                ->name('reject');

            Route::delete('/{leave}', [LeaveController::class, 'destroy'])
                ->middleware('permission:leaves.create')
                ->name('destroy');
        });

    // Payroll placeholder
    Route::middleware('permission:payroll.view')->group(function () {
        Route::get('/payroll', function () {
            return 'Payroll Management';
        })->name('payroll.index');
    });
});

require __DIR__.'/auth.php';
