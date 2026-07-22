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
        Schema::create('event_schedules', function (Blueprint $table) {

            $table->id();

            // Parent Request
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();

            // Coverage Title
            $table->string('title');

            // Venue
            $table->string('venue');

            // Schedule
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Time
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Contact Person
            $table->string('contact_person')->nullable();

            // Contact Number
            $table->string('contact_number')->nullable();

            // Assigned Staff
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Coverage Status
            $table->enum('coverage_status', [
                'Pending',
                'Covered',
                'Cancelled'
            ])->default('Pending');

            // Date & Time Covered
            $table->timestamp('covered_at')->nullable();

            // Remarks
            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};