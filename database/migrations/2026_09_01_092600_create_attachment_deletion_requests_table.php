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
        Schema::create('attachment_deletion_requests', function (Blueprint $table) {
            $table->id();

            // Attachment being requested for deletion
            $table->foreignId('attachment_id')
                ->constrained('attachments')
                ->cascadeOnDelete();

            // User who requested the deletion
            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Reason provided by the staff member
            $table->text('reason');

            // Pending, Approved, or Rejected
            $table->string('status')->default('Pending');

            // Admin who reviewed the request
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Admin's optional response/reason
            $table->text('review_note')->nullable();

            // When the Admin reviewed the request
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment_deletion_requests');
    }
};