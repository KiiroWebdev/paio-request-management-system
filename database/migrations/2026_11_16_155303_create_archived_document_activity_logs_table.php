<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archived_document_activity_logs', function (Blueprint $table) {
            $table->id();

            // Related Archived Document
            $table->foreignId('archived_document_id')
                ->nullable()
                ->constrained('archived_documents')
                ->nullOnDelete();

            // User who performed the action
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Activity Details
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_document_activity_logs');
    }
};