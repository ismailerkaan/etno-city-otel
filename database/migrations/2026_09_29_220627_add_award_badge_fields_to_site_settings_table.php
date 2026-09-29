<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('award_badge_title')->nullable()->after('suite_count');
            $table->string('award_badge_subtitle')->nullable()->after('award_badge_title');
            $table->string('award_badge_title_en')->nullable()->after('award_badge_subtitle');
            $table->string('award_badge_subtitle_en')->nullable()->after('award_badge_title_en');
        });

        // Set default values for existing row
        DB::table('site_settings')->where('id', 1)->update([
            'award_badge_title' => '1 Numaralı Sığınak Seçildi',
            'award_badge_subtitle' => 'Akdeniz Butik Sığınak & Mimarlık Ödülü 2024',
            'award_badge_title_en' => 'Voted #1 Sanctuary',
            'award_badge_subtitle_en' => 'Mediterranean Boutique Sanctuary & Architecture Award 2024',
        ]);
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'award_badge_title',
                'award_badge_subtitle',
                'award_badge_title_en',
                'award_badge_subtitle_en',
            ]);
        });
    }
};
