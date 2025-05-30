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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->enum(
                'grade_level',
                [
                    'kindergarten',
                    'grade1',
                    'grade2',
                    'grade3',
                    'grade4',
                    'grade5',
                    'grade6'
                ]
            );

            $table->enum('section', ['A', 'B', 'C', 'D', 'E', 'F']);

            $table->timestamps();

            // Prevent multiple classes with the same grade_level + section + school_year
            $table->unique(['grade_level', 'section'], 'unique_class_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
