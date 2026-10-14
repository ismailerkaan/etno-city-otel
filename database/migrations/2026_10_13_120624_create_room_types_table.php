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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('nightly_price', 12, 2);
            $table->char('currency', 3)->default('TRY');
            $table->unsignedTinyInteger('max_adults')->default(2);
            $table->unsignedTinyInteger('max_children')->default(0);
            $table->unsignedSmallInteger('size_sqm')->nullable();
            $table->string('bed_type')->nullable();
            $table->string('bed_type_en')->nullable();
            $table->json('features')->nullable();
            $table->json('features_en')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
