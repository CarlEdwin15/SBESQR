<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->id();

            // Links to attendance, student and parent (users)
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            // parent_id references users table (parents are users in your users table)
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');

            // Destination phone (store snapshot at creation because parent's phone may change later)
            $table->string('phone', 30)->nullable()->index();

            // The SMS payload / message content (store final text sent)
            $table->text('message');

            // Provider-specific metadata
            $table->string('provider', 50)->default('twilio'); // provider label
            $table->string('provider_message_id')->nullable()->index(); // id returned by provider
            $table->json('provider_response')->nullable(); // raw provider response for debugging

            // Sending status: pending -> sent -> failed
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending')->index();

            // Number of attempts
            $table->unsignedTinyInteger('attempts')->default(0);

            // When provider actually accepted or we attempted sending
            $table->timestamp('sent_at')->nullable();

            // Optional scheduled sending time (if you implement scheduling)
            $table->timestamp('scheduled_at')->nullable()->index();

            // Useful administrative fields
            $table->text('error_message')->nullable();

            $table->timestamps();

            // Useful composite index
            $table->index(['status', 'scheduled_at', 'attempts']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_notifications');
    }
};
