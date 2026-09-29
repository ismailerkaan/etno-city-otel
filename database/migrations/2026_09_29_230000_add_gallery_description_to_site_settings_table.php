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
            $table->text('gallery_description')->nullable()->after('gallery_title');
            $table->text('gallery_description_en')->nullable()->after('gallery_title_en');
        });

        DB::table('site_settings')->where('id', 1)->update([
            'gallery_description' => "Ege'nin dingin sığınağını keşfedin. Mimari süitlerimiz, gizli koylarımız, arındırıcı sağlık alanlarımız ve Akdeniz lezzet sanatının görsel yolculuğu.",
            'gallery_description_en' => 'Immerse in the serene Aegean sanctuary. Explore our architectural suites, secluded coves, tranquil wellness spaces, and Mediterranean culinary artistry.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['gallery_description', 'gallery_description_en']);
        });
    }
};
