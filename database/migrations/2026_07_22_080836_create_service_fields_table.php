<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_fields', function (Blueprint $table) {

            $table->id();

            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('field_type_id')
                ->constrained()
                ->cascadeOnDelete();

            // Database column name
            $table->string('field_name');

            // Label shown to users
            $table->string('label');

            $table->boolean('required')->default(false);

            $table->string('placeholder')->nullable();

            $table->text('help_text')->nullable();

            $table->integer('display_order')->default(0);

            $table->boolean('active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_fields');
    }
};