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

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            $table->unique('name'); // avoid duplicate subject definitions
        });

        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['class_id', 'subject_id', 'school_year_id'], 'unique_subject_per_class_sy');
        });

        Schema::create('quarters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_subject_id')->constrained('class_subject')->onDelete('cascade');
            $table->unsignedTinyInteger('quarter'); // 1 = 1st, 2 = 2nd, 3 = 3rd, 4 = 4th
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['upcoming', 'active', 'closed'])->default('upcoming');
            $table->timestamps();

            $table->unique(['class_subject_id', 'quarter'], 'unique_quarter_per_class_subject');
        });

        Schema::create('quarterly_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('quarter_id')->constrained('quarters')->onDelete('cascade');
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'quarter_id'], 'unique_student_per_quarter');
        });

        Schema::create('final_subject_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_subject_id')->constrained('class_subject')->onDelete('cascade');
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->enum('remarks', ['passed', 'failed'])->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'class_subject_id'], 'unique_final_subject_grade');
        });

        Schema::create('general_averages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->decimal('general_average', 5, 2)->nullable();
            $table->enum('remarks', ['passed', 'failed'])->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'school_year_id'], 'unique_general_average_per_sy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_averages');
        Schema::dropIfExists('final_subject_grades');
        Schema::dropIfExists('quarterly_grades');
        Schema::dropIfExists('quarters');
        Schema::dropIfExists('class_subject');
        Schema::dropIfExists('subjects');
    }
};
