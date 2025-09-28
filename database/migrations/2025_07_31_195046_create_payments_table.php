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

            $table->foreignId('class_student_id')
                ->constrained('class_student')
                ->onDelete('cascade');

            // Payment details
            $table->string('payment_name'); // e.g. "June Tuition"
            $table->decimal('amount_due', 10, 2);

            $table->date('date_created'); // When it was posted
            $table->date('due_date');     // Deadline to pay

            // Student payment progress (handled by teacher)
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('date_paid')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_student_id', 'payment_name'], 'unique_payment_entry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
