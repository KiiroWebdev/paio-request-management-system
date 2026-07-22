<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->string('category')->nullable();

            $table->string('icon')->default('bi-grid');

            $table->string('color')->default('primary');

            $table->integer('sort_order')->default(0);

            $table->boolean('requires_request')->default(true);
            $table->boolean('requires_organization')->default(true);
            $table->boolean('requires_contact')->default(true);
            $table->boolean('requires_schedule')->default(false);
            $table->boolean('requires_attachments')->default(false);
            $table->boolean('requires_event_details')->default(false);
            $table->boolean('requires_approval')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};