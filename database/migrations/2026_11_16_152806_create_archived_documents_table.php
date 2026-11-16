<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archived_documents', function (Blueprint $table) {
            $table->id();

            // Document Information
            $table->string('document_type', 100);
            $table->string('document_number')->nullable();
            $table->string('subject');
            $table->date('document_date')->nullable();

            // Office Information
            $table->string('originating_office')->nullable();
            $table->string('recipient')->nullable();

            // Additional Information
            $table->text('remarks')->nullable();

            // Private PDF File Information
            $table->string('file_path');
            $table->string('original_file_name');
            $table->unsignedBigInteger('file_size')->nullable();

            // User Tracking
            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Timestamps and Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes for Searching and Filtering
            $table->index('document_type');
            $table->index('document_number');
            $table->index('document_date');
            $table->index('originating_office');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_documents');
    }
};