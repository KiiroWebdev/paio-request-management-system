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
        Schema::create('request_answers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('request_id')
                ->constrained('requests')
                ->cascadeOnDelete();

            $table->foreignId('service_field_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->longText('value')->nullable();

            $table->timestamps();

            $table->unique([
                'request_id',
                'service_field_id',
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_answers');
    }
};