<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_settings')->update([
            'about_image_url' => 'home/etnocity-about.jpg',
            'updated_at' => now(),
        ]);

        DB::table('hero_slides')->update([
            'is_active' => false,
            'updated_at' => now(),
        ]);

        DB::table('hero_slides')->updateOrInsert(
            ['image_path' => 'home/etnocity-hero.jpg'],
            [
                'title' => null,
                'title_en' => null,
                'description' => null,
                'description_en' => null,
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hero_slides')
            ->where('image_path', 'home/etnocity-hero.jpg')
            ->delete();

        DB::table('hero_slides')->update([
            'is_active' => true,
            'updated_at' => now(),
        ]);

        DB::table('site_settings')->update([
            'about_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-8hMf1SQTks238yLzKOTSLidNO71s6HCPe6Ti_XiqSuZOKLCq20yYyDyUda6uLqDtwTBzJjG_0D0QxjPEOrx7gdVX_5aJHeUR3DVuF0DCQE8U6mxoGvLpJtjcdkfhOCoTWAfW5O4V_sKAZy2rJfjQoBGAPt4qaFu8fV4nOOAgJN1gGlA1bxidXKYrdIGTq_nNm06PGomfuyYgoNIyy0ewjogF7z61tXE0TZ89CXeW7-EXoBen7FlpJw',
            'updated_at' => now(),
        ]);
    }
};
