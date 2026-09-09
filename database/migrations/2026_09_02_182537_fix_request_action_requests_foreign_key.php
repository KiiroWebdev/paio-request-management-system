<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('request_action_requests', function (Blueprint $table) {
            $table->dropForeign(['document_request_id']);
        });

        Schema::table('request_action_requests', function (Blueprint $table) {
            $table->foreign('document_request_id')
                ->references('id')
                ->on('requests')
                ->cascadeOnDelete();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('request_action_requests', function (Blueprint $table) {
            $table->dropForeign(['document_request_id']);
        });

        Schema::table('request_action_requests', function (Blueprint $table) {
            $table->foreign('document_request_id')
                ->references('id')
                ->on('document_requests')
                ->cascadeOnDelete();
        });

        Schema::enableForeignKeyConstraints();
    }
};