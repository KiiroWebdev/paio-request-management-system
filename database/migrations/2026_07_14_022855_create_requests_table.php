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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            // Control Number
            $table->string('control_no')->unique();

            // Date request was received
$table->date('date_received');

// Staff who received the request
$table->foreignId('received_by')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();

// Internal or External
$table->enum('request_type', [
    'Internal',
    'External'
]);

// Has request letter?
$table->boolean('has_request_letter')->default(false);

            $table->string('title');
            // Request Information
            $table->foreignId('organization_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

            $table->foreignId('contact_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();
            
            $table->string('subject');
            $table->text('description')->nullable();

            // Workflow Status
            $table->enum('status', [
                'Incoming',
                'Ongoing',
                'Done',
                'Archived'
            ])->default('Incoming');

            // Priority
            $table->enum('priority', [
                'Low',
                'Normal',
                'High',
                'Urgent'
            ])->default('Normal');

            // Dates
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            // User who created the request
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};