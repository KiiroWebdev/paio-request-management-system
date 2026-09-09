<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachment_deletion_requests', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('attachment_id');
        });
    }

    public function down(): void
    {
        Schema::table('attachment_deletion_requests', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
};