<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('gallery_categories')
            ->where('slug', 'suites')
            ->value('id');

        if ($categoryId === null) {
            return;
        }

        DB::table('gallery_images')->updateOrInsert(
            ['image_path' => 'gallery/etnocity-window-room.jpg'],
            [
                'gallery_category_id' => $categoryId,
                'tag' => 'Aydınlık ve Ferah Odalar',
                'tag_en' => 'Bright and Spacious Rooms',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    public function down(): void
    {
        DB::table('gallery_images')
            ->where('image_path', 'gallery/etnocity-window-room.jpg')
            ->delete();
    }
};
