<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;

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

    // Payroll placeholder
    Route::middleware('permission:payroll.view')->group(function () {
        Route::get('/payroll', function () {
            return 'Payroll Management';
        })->name('payroll.index');
    });
});

require __DIR__.'/auth.php';