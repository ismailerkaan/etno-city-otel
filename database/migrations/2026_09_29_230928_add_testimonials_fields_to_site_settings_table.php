<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('testimonials_eyebrow')->nullable()->after('gallery_description');
            $table->text('testimonials_title')->nullable()->after('testimonials_eyebrow');
            $table->text('testimonials_description')->nullable()->after('testimonials_title');
            $table->text('testimonials_eyebrow_en')->nullable()->after('gallery_description_en');
            $table->text('testimonials_title_en')->nullable()->after('testimonials_eyebrow_en');
            $table->text('testimonials_description_en')->nullable()->after('testimonials_title_en');
        });

        DB::table('site_settings')->where('id', 1)->update([
            'testimonials_eyebrow' => 'Misafir Yansımaları',
            'testimonials_title' => 'Eşsiz Deneyimler & Hatıralar',
            'testimonials_description' => "Misafirlerimizin kaleminden Cape Artemis'in dinginliğine ve ruhani zarafetine dair içten izlenimler.",
            'testimonials_eyebrow_en' => 'Guest Reflections',
            'testimonials_title_en' => 'Timeless Impressions',
            'testimonials_description_en' => 'Curated reflections from travelers who experienced the tranquil luxury and architectural serenity of Cape Artemis.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'testimonials_eyebrow',
                'testimonials_title',
                'testimonials_description',
                'testimonials_eyebrow_en',
                'testimonials_title_en',
                'testimonials_description_en',
            ]);
        });
    }
};
