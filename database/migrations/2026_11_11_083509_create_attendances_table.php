<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete();

            $table->date('attendance_date');

            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();

            $table->string('status', 20)->default('present');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['employee_id', 'attendance_date'],
                'attendance_employee_date_unique'
            );

            $table->index(['attendance_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
