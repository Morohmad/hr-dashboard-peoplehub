<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Employee Management
    Route::middleware('permission:employees.view')
        ->group(function () {

            Route::get('/employees', function () {
                return 'Employee Management';
            })->name('employees.index');

        });

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