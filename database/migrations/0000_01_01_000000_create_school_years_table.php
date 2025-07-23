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
            $table->string('school_year')->unique(); // e.g. "2024-2025"
            $table->date('start_date')->nullable(); // e.g. "2024-06-01"
            $table->date('end_date')->nullable(); // e.g. "2025-03-31"
            $table->timestamps();
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
