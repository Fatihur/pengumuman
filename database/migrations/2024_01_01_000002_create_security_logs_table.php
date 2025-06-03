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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50); // login_attempt, suspicious_activity, etc.
            $table->string('severity', 20)->default('medium'); // low, medium, high, critical
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('email')->nullable();
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('occurred_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['event_type', 'occurred_at']);
            $table->index(['severity', 'occurred_at']);
            $table->index(['ip_address', 'occurred_at']);
            $table->index(['email', 'occurred_at']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
