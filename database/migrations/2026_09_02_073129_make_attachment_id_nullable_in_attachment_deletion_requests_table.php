<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachment_deletion_requests', function (Blueprint $table) {

            // Remove the existing foreign key
            $table->dropForeign(['attachment_id']);

            // Allow attachment_id to become NULL
            $table->unsignedBigInteger('attachment_id')
                ->nullable()
                ->change();

            // Recreate the foreign key
            $table->foreign('attachment_id')
                ->references('id')
                ->on('attachments')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attachment_deletion_requests', function (Blueprint $table) {

            // Remove the nullable foreign key
            $table->dropForeign(['attachment_id']);

            // Make attachment_id required again
            $table->unsignedBigInteger('attachment_id')
                ->nullable(false)
                ->change();

            // Restore cascade behavior
            $table->foreign('attachment_id')
                ->references('id')
                ->on('attachments')
                ->cascadeOnDelete();
        });
    }
};