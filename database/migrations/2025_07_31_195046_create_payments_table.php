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
            $table->date('due_date');     // Deadline to pay

            // Student payment progress (handled by teacher)
            // $table->decimal('amount_paid', 10, 2)->default(0);
            // $table->date('date_paid')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_student_id', 'payment_name'], 'unique_payment_entry');
        });

        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();

            // Link to the original payment
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->enum('payment_method', ['cash_on_hand', 'gcash', 'paymaya'])->default('cash_on_hand');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');

            $table->decimal('amount_paid', 10, 2);
            $table->datetime('payment_date')->default(now());

            $table->timestamps();
        });

        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');

            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['gcash', 'paymaya']);
            $table->string('reference_number')->nullable();
            $table->string('receipt_image')->nullable(); // for proof upload
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending');
            $table->unsignedInteger('attempt_number')->default(1);
            $table->text('admin_remarks')->nullable();
            $table->datetime('requested_at')->default(now());
            $table->datetime('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
        Schema::dropIfExists('payments');
    }
};
