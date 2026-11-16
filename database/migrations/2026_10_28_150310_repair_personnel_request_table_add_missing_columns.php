<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnel_request', function (Blueprint $table) {
            $table->foreignId('request_id')
                ->nullable()
                ->constrained('requests')
                ->cascadeOnDelete();

            $table->foreignId('personnel_id')
                ->nullable()
                ->constrained('personnels')
                ->cascadeOnDelete();
        });

        // Existing rows, if any, cannot be linked automatically.
        // The table is expected to be empty because assignments have not worked yet.
        DB::table('personnel_request')->delete();

        Schema::table('personnel_request', function (Blueprint $table) {
            $table->unique(['request_id', 'personnel_id']);
        });
    }

    public function down(): void
    {
        Schema::table('personnel_request', function (Blueprint $table) {
            $table->dropUnique(['request_id', 'personnel_id']);
            $table->dropForeign(['request_id']);
            $table->dropForeign(['personnel_id']);
            $table->dropColumn(['request_id', 'personnel_id']);
        });
    }
};