<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = now();

        DB::table('site_settings')->update([
            'about_image_url' => 'home/etnocity-hotel-lobby.jpg',
            'updated_at' => $timestamp,
        ]);

        DB::table('hero_slides')->update([
            'is_active' => false,
            'updated_at' => $timestamp,
        ]);

        DB::table('hero_slides')->updateOrInsert(
            ['image_path' => 'home/etnocity-hotel-view.jpg'],
            [
                'title' => null,
                'title_en' => null,
                'description' => null,
                'description_en' => null,
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        );

        DB::table('gallery_images')->update([
            'is_active' => false,
            'updated_at' => $timestamp,
        ]);

        $categoryIds = DB::table('gallery_categories')->pluck('id', 'slug');
        $images = [
            ['suites', 'gallery/etnocity-double-room.jpg', 'Konforlu Odalar', 'Comfortable Rooms', 1],
            ['suites', 'gallery/etnocity-single-room.jpg', 'Tek Kişilik Oda', 'Single Room', 2],
            ['suites', 'gallery/etnocity-bathroom.jpg', 'Modern Banyo', 'Modern Bathroom', 3],
            ['wellness', 'gallery/etnocity-lobby.jpg', 'Lobi ve Ortak Alanlar', 'Lobby and Shared Spaces', 4],
            ['grounds', 'gallery/etnocity-city-view.jpg', 'Şehir Manzarası', 'City View', 5],
        ];

        foreach ($images as [$categorySlug, $imagePath, $tag, $tagEn, $sortOrder]) {
            $categoryId = $categoryIds->get($categorySlug);

            if ($categoryId === null) {
                continue;
            }

            DB::table('gallery_images')->updateOrInsert(
                ['image_path' => $imagePath],
                [
                    'gallery_category_id' => $categoryId,
                    'tag' => $tag,
                    'tag_en' => $tagEn,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ],
            );
        }
    }

    public function down(): void
    {
        DB::table('gallery_images')
            ->whereIn('image_path', [
                'gallery/etnocity-double-room.jpg',
                'gallery/etnocity-single-room.jpg',
                'gallery/etnocity-bathroom.jpg',
                'gallery/etnocity-lobby.jpg',
                'gallery/etnocity-city-view.jpg',
            ])
            ->delete();

        DB::table('gallery_images')->update([
            'is_active' => true,
            'updated_at' => now(),
        ]);

        DB::table('hero_slides')
            ->where('image_path', 'home/etnocity-hotel-view.jpg')
            ->delete();

        DB::table('hero_slides')->update([
            'is_active' => false,
            'updated_at' => now(),
        ]);

        DB::table('hero_slides')
            ->where('image_path', 'home/etnocity-hero.jpg')
            ->update([
                'is_active' => true,
                'updated_at' => now(),
            ]);

        DB::table('site_settings')->update([
            'about_image_url' => 'home/etnocity-about.jpg',
            'updated_at' => now(),
        ]);
    }
};
