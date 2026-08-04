<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('short_name')->nullable();

            $table->enum('type', [
    'Department',
    'Office',
    'Division',
    'Section',
    'Barangay',
    'National Agency',
    'GOCC',
    'School',
    'University',
    'Hospital',
    'Private Company',
    'NGO',
    "Peoples Organization",
    'Media',
    'Individual',
    'Other',
]);

            $table->boolean('is_internal')->default(false);

            $table->boolean('is_active')->default(true);

            $table->text('address')->nullable();

            $table->string('telephone')->nullable();

            $table->string('email')->nullable();

            $table->string('website')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};