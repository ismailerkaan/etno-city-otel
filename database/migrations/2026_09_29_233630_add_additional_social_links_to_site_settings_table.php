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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('facebook_url')->nullable()->after('whatsapp_url');
            $table->text('youtube_url')->nullable()->after('facebook_url');
            $table->text('tripadvisor_url')->nullable()->after('youtube_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['facebook_url', 'youtube_url', 'tripadvisor_url']);
        });
    }
};
