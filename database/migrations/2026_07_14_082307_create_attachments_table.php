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
        Schema::create('attachments', function (Blueprint $table) {

            $table->id();

            // Parent Request
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();

            // Original filename
            $table->string('file_name');

            // Storage path
            $table->string('file_path');

            // Extension (pdf, jpg, png, docx...)
            $table->string('file_type', 20)->nullable();

            // Size in bytes
            $table->unsignedBigInteger('file_size')->nullable();

            // Attachment classification
            $table->enum('attachment_type', [
                'Request Letter',
                'Program',
                'Layout',
                'Memorandum',
                'Photo',
                'Video',
                'Document',
                'Others'
            ])->default('Document');

            // Uploaded by
            $table->foreignId('uploaded_by')
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
        Schema::dropIfExists('attachments');
    }
};
