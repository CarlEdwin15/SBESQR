<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create 'addresses' table
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('house_no')->nullable();
            $table->string('street_name')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Philippines');
            $table->string('zip_code')->nullable();
            $table->string('pob')->nullable();
            $table->timestamps();
        });

        // 2. Create 'students' table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_lrn', 20)->unique();
            $table->string('student_lName');
            $table->string('student_fName');
            $table->string('student_mName')->nullable();
            $table->string('student_extName', 45)->nullable();
            $table->date('student_dob')->nullable();
            $table->enum('student_sex', ['male', 'female']);
            $table->string('qr_code')->nullable()->unique();
            $table->string('student_photo', 2048)->nullable();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Pivot: class_student
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->enum('enrollment_status', ['enrolled', 'not_enrolled', 'archived', 'graduated'])->default('enrolled');
            $table->enum('enrollment_type', ['regular', 'transferee', 'returnee'])->default('regular');
            $table->timestamps();

            $table->unique(['student_id', 'class_id', 'school_year_id'], 'unique_class_student_sy');
        });

        // 4. Pivot: student_parent (many-to-many relationship between users and students)
        Schema::create('student_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['student_id', 'parent_id'], 'unique_student_parent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parent');
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('students');
        Schema::dropIfExists('addresses');
    }
};
