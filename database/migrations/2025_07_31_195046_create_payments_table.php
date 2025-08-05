<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Who defined the payment (Admin)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Applies to which class and school year
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');

            // Student for whom the payment record is being tracked
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            // Payment details
            $table->string('payment_name'); // e.g. "June Tuition"
            $table->decimal('amount_due', 10, 2);

            $table->date('date_created'); // When it was posted
            $table->date('due_date');     // Deadline to pay

            // Student payment progress (handled by teacher)
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('date_paid')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate payment entries for a student and same payment instance
            $table->unique(['class_id', 'school_year_id', 'student_id', 'payment_name'], 'unique_payment_entry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
