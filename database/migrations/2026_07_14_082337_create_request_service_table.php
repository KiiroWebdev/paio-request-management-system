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
        Schema::create('request_service', function (Blueprint $table) {

            $table->id();

            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();

            $table->foreignId('service_id')
                  ->constrained('services')
                  ->cascadeOnDelete();

            // Prevent duplicate service assignment
            $table->unique(['request_id', 'service_id']);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_service');
    }
};