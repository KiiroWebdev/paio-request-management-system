<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('field_types')->insert([
            'name' => 'Schedule',
            'key' => 'schedule',
            'icon' => 'bi-calendar2-week',
            'description' => 'Multiple dates with start and end times',
        ]);
    }

    public function down(): void
    {
        DB::table('field_types')
            ->where('key', 'schedule')
            ->delete();
    }
};