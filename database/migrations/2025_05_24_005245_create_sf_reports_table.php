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
        Schema::create('sf_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->enum('form_type', ['SF1', 'SF2']);
            $table->string('school_year');
            $table->enum('month', [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ]);
            $table->enum('export_format', ['PDF', 'Excel']);
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->string('file_path', 2048);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sf_reports');
    }
};
