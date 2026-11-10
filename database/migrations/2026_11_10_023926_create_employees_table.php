<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Account relationship
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained()
                ->nullOnDelete();

            // Employee identity
            $table->string('employee_code', 30)->unique();

            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();

            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();

            // Personal information
            $table->date('birth_date')->nullable();

            $table->enum('gender', [
                'male',
                'female',
                'other',
            ])->nullable();

            $table->text('address')->nullable();

            // Employment information
            $table->foreignId('department_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('manager_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->date('hire_date');

            $table->enum('employment_type', [
                'permanent',
                'contract',
                'internship',
                'probation',
            ])->default('permanent');

            $table->enum('employment_status', [
                'active',
                'inactive',
                'resigned',
                'terminated',
            ])->default('active');

            $table->string('photo')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'employment_status']);
            $table->index(['position_id', 'employment_status']);
            $table->index('hire_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};