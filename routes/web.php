<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
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
    Route::middleware('permission:departments.view')
        ->group(function () {

            Route::get('/departments', function () {
                return 'Department Management';
            })->name('departments.index');

        });

    // Payroll Management
    Route::middleware('permission:payroll.view')
        ->group(function () {

            Route::get('/payroll', function () {
                return 'Payroll Management';
            })->name('payroll.index');

        });

});

require __DIR__.'/auth.php';
