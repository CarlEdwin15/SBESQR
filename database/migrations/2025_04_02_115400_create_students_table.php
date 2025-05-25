<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('students', function (Blueprint $table) {
    //         $table->bigIncrements('student_id');
    //         $table->string('student_lrn', 12)->unique();
    //         $table->enum('student_grade_level', [
    //             'kindergarten',
    //             'grade1',
    //             'grade2',
    //             'grade3',
    //             'grade4',
    //             'grade5',
    //             'grade6'
    //         ])->nullable();
    //         $table->enum('student_section', [
    //             'A',
    //             'B',
    //             'C',
    //             'D',
    //             'E',
    //             'F',
    //         ])->nullable();
    //         $table->string('student_fName', 255);
    //         $table->string('student_lName', 255);
    //         $table->string('student_mName', 255)->nullable();
    //         $table->string('student_extName', 45)->nullable();
    //         $table->date('student_dob')->nullable();
    //         $table->enum('student_sex', ['Male', 'Female']);
    //         $table->integer('student_age')->nullable();
    //         $table->string('student_pob', 255)->nullable();
    //         $table->string('student_address', 255)->nullable();
    //         $table->string('student_fatherFName', 255)->nullable();
    //         $table->string('student_fatherLName', 255)->nullable();
    //         $table->string('student_fatherMName', 255)->nullable();
    //         $table->string('student_motherFName', 255)->nullable();
    //         $table->string('student_motherLName', 255)->nullable();
    //         $table->string('student_motherMName', 255)->nullable();
    //         $table->string('student_parentPhone', 255)->nullable();
    //         $table->string('qr_code', 255)->unique();
    //         $table->string('student_photo', 2048)->nullable();
    //         $table->timestamps();
    //     });
    // }

    public function up(): void
    {
        // 1. Create 'addresses' table first
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

        // 2. Create 'parent_info' table second
        Schema::create('parent_info', function (Blueprint $table) {
            $table->id();
            $table->string('father_fName')->nullable();
            $table->string('father_mName')->nullable();
            $table->string('father_lName')->nullable();
            $table->string('father_phone')->nullable();

            $table->string('mother_fName')->nullable();
            $table->string('mother_mName')->nullable();
            $table->string('mother_lName')->nullable();
            $table->string('mother_phone')->nullable();

            $table->string('guardian_fName')->nullable();
            $table->string('guardian_mName')->nullable();
            $table->string('guardian_lName')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->timestamps();
        });

        // 3. Create 'students' table last
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('student_lrn', 20)->unique();
            $table->string('student_lName');
            $table->string('student_fName');
            $table->string('student_mName')->nullable();
            $table->string('student_extName', 45)->nullable();
            $table->date('student_dob')->nullable();
            $table->enum('student_sex', ['male', 'female']);
            $table->string('qr_code')->nullable();
            $table->string('student_photo', 2048)->nullable();

            // One-to-one relationships via foreign keys
            $table->foreignId('address_id')->unique()->constrained('addresses')->onDelete('cascade');
            $table->foreignId('parent_id')->unique()->constrained('parent_info')->onDelete('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop students first (depends on other tables)
        Schema::dropIfExists('students');
        Schema::dropIfExists('parent_info');
        Schema::dropIfExists('addresses');
    }
};
