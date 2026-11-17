<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('document_categories')->insert([
            ['name' => 'Memoranda', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Office Orders', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Letters', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Executive Orders', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Resolutions', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ordinances', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reports', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Minutes of Meetings', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Invitations', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communications', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Certificates', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agreements', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other Documents', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};