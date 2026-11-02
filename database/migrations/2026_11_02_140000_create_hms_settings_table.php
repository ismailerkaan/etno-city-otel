<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hms_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('base_url')->default('https://test.hms.gen.tr');
            $table->string('hotel_code', 50)->nullable();
            // Encrypted with APP_KEY
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->text('last_test_result')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hms_settings');
    }
};
