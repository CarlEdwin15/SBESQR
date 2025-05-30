<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();

            // School year period (e.g. 2024-2025)
            $table->string('school_year_label'); // e.g. "2024-2025"
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Teacher assigned to this class/school year
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

            // Class assigned for this school year
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['school_year_label', 'teacher_id', 'class_id'], 'unique_sy_teacher_class');
        });

        // Pivot table to assign students to specific school_years
        Schema::create('school_year_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['school_year_id', 'student_id'], 'unique_sy_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_year_student');
        Schema::dropIfExists('school_years');
    }
};
