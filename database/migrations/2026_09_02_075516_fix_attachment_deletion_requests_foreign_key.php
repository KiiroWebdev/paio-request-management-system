<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::rename(
            'attachment_deletion_requests',
            'attachment_deletion_requests_old'
        );

        Schema::create('attachment_deletion_requests', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('attachment_id')->nullable();

            $table->foreign('attachment_id')
                ->references('id')
                ->on('attachments')
                ->nullOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('reason');

            $table->string('status');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('review_note')->nullable();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });

        DB::statement('
            INSERT INTO attachment_deletion_requests
            (
                id,
                attachment_id,
                requested_by,
                reason,
                status,
                reviewed_by,
                review_note,
                reviewed_at,
                created_at,
                updated_at
            )
            SELECT
                id,
                attachment_id,
                requested_by,
                reason,
                status,
                reviewed_by,
                review_note,
                reviewed_at,
                created_at,
                updated_at
            FROM attachment_deletion_requests_old
        ');

        Schema::drop('attachment_deletion_requests_old');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::rename(
            'attachment_deletion_requests',
            'attachment_deletion_requests_old'
        );

        Schema::create('attachment_deletion_requests', function (Blueprint $table) {

            $table->id();

            $table->foreignId('attachment_id')
                ->constrained('attachments')
                ->cascadeOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('reason');

            $table->string('status');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('review_note')->nullable();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });

        DB::statement('
            INSERT INTO attachment_deletion_requests
            (
                id,
                attachment_id,
                requested_by,
                reason,
                status,
                reviewed_by,
                review_note,
                reviewed_at,
                created_at,
                updated_at
            )
            SELECT
                id,
                attachment_id,
                requested_by,
                reason,
                status,
                reviewed_by,
                review_note,
                reviewed_at,
                created_at,
                updated_at
            FROM attachment_deletion_requests_old
        ');

        Schema::drop('attachment_deletion_requests_old');

        Schema::enableForeignKeyConstraints();
    }
};