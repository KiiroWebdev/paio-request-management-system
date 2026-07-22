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
        Schema::create('contacts', function (Blueprint $table) {
           $table->id();

$table->foreignId('organization_id')
      ->constrained()
      ->cascadeOnDelete();

$table->string('first_name');

$table->string('middle_name')->nullable();

$table->string('last_name');

$table->string('suffix')->nullable();

$table->string('position');

$table->string('office')->nullable();

$table->string('email')->nullable();

$table->string('mobile')->nullable();

$table->string('telephone')->nullable();

$table->boolean('is_primary')
      ->default(false);

$table->boolean('is_active')
      ->default(true);

$table->text('remarks')->nullable();

$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
