<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel_request', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_id')
                ->constrained('requests')
                ->cascadeOnDelete();

            $table->foreignId('personnel_id')
                ->constrained('personnels')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['request_id', 'personnel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel_request');
    }
};