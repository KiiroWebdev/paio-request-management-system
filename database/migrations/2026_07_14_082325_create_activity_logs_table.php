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
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id();

            // Related Request
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();

            // User who performed the action
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Action performed
            $table->string('action');

            // Detailed description
            $table->text('description')->nullable();

            // Previous value (if applicable)
            $table->text('old_value')->nullable();

            // New value (if applicable)
            $table->text('new_value')->nullable();

            // IP Address (optional)
            $table->string('ip_address')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};