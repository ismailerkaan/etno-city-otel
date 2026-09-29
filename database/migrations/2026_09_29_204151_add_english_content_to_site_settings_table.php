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
            $table->string('hero_eyebrow_en')->nullable()->after('hero_eyebrow');
            $table->string('hero_title_en')->nullable()->after('hero_title');
            $table->text('hero_subtitle_en')->nullable()->after('hero_subtitle');
            $table->string('about_eyebrow_en')->nullable()->after('about_eyebrow');
            $table->string('about_title_en')->nullable()->after('about_title');
            $table->text('about_primary_text_en')->nullable()->after('about_primary_text');
            $table->text('about_secondary_text_en')->nullable()->after('about_secondary_text');
            $table->string('newsletter_title_en')->nullable()->after('newsletter_title');
            $table->text('newsletter_text_en')->nullable()->after('newsletter_text');
        });

        DB::table('site_settings')->where('id', 1)->update([
            'hero_eyebrow_en' => 'Bodrum Peninsula · Cape Artemis',
            'hero_title_en' => 'A Timeless Escape, Designed for You',
            'hero_subtitle_en' => 'Aegean serenity meets architectural elegance in a sanctuary designed for slow living and sensory renewal.',
            'about_eyebrow_en' => 'Welcome to EtnoCity Hotel',
            'about_title_en' => 'Created in Harmony with Sea and Living Stone',
            'about_primary_text_en' => 'Set on the untouched cliffs of northern Bodrum, EtnoCity Hotel unfolds as a dialogue between raw Mediterranean elements and refined architecture.',
            'about_secondary_text_en' => 'Here, time resets. Mornings begin with the Aegean horizon; afternoons drift between saltwater pools and ancient olive groves.',
            'newsletter_title_en' => 'Invitations to Stillness',
            'newsletter_text_en' => 'Discover seasonal stories, architectural previews and private stay releases before anyone else.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_eyebrow_en',
                'hero_title_en',
                'hero_subtitle_en',
                'about_eyebrow_en',
                'about_title_en',
                'about_primary_text_en',
                'about_secondary_text_en',
                'newsletter_title_en',
                'newsletter_text_en',
            ]);
        });
    }
};
