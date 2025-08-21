<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('endpoint')->unique();
            $table->string('public_key');      // p256dh
            $table->string('auth_token');      // auth
            $table->string('content_encoding')->nullable();
            $table->timestamp('expiration_time')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
