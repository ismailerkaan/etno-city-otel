<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')->constrained('menu_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('description')->nullable();
            $table->string('description_en')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // Cafe & Lounge menüsünün ilk içeriği
        $menu = [
            ['Sıcak İçecekler', 'Hot Drinks', [
                ['Demleme Türk Çayı', 'Brewed Turkish Tea', 40],
                ['Fincan Çay (Bitki / Yeşil / Meyve)', 'Cup of Tea (Herbal / Green / Fruit)', 70],
                ['Türk Kahvesi (Lokum ile)', 'Turkish Coffee (with Turkish Delight)', 90],
                ['Duble Türk Kahvesi', 'Double Turkish Coffee', 110],
                ['Filtre Kahve', 'Filter Coffee', 90],
                ['Sütlü Filtre Kahve', 'Filter Coffee with Milk', 100],
                ['Espresso', 'Espresso', 80],
                ['Double Espresso', 'Double Espresso', 110],
                ['Lungo', 'Lungo', 90],
                ['Americano', 'Americano', 90],
                ['Sütlü Kahve', 'Coffee with Milk', 100],
                ['Espresso Macchiato', 'Espresso Macchiato', 100],
                ['Flat White', 'Flat White', 110],
                ['Café Latte', 'Café Latte', 115],
                ['Cappuccino', 'Cappuccino', 115],
                ['Mocha / White Chocolate Mocha', 'Mocha / White Chocolate Mocha', 130],
                ['Sıcak Çikolata', 'Hot Chocolate', 110],
            ]],
            ['Soğuk İçecekler', 'Cold Drinks', [
                ['Ev Yapımı Limonata / Nane & Çilekli', 'Homemade Lemonade / Mint & Strawberry', 90],
                ['Taze Sıkılmış Portakal Suyu / Meyve Kokteyli', 'Fresh Orange Juice / Fruit Cocktail', 130],
                ['Pepsi (Klasik / Max Şekersiz)', 'Pepsi (Classic / Max Sugar Free)', 80],
                ['Yedigün Portakal / 7UP', 'Yedigün Orange / 7UP', 80],
                ['Lipton Ice Tea Çeşitleri', 'Lipton Ice Tea Varieties', 80],
                ['Tropicana Kutu Meyve Suları', 'Tropicana Fruit Juices (Can)', 80],
                ['Uludağ Cam Şişe Gazoz (Elvane / Portakal)', 'Uludağ Soda Pop in Glass Bottle (Elvane / Orange)', 80],
                ['Juss Cam Şişe Meyve Suları', 'Juss Fruit Juices in Glass Bottle', 80],
                ['Uludağ Meyveli Soda Çeşitleri', 'Uludağ Flavoured Mineral Water', 60],
                ['Uludağ Sade Maden Suyu', 'Uludağ Plain Mineral Water', 40],
                ['Ayran', 'Ayran', 50],
                ['Su (Pet Şişe)', 'Water (Bottle)', 30],
            ]],
            ['Tatlılar & Kurabiyeler', 'Desserts & Bakery', [
                ['Klasik Waffle (Mevsim Meyveleri & Çikolata)', 'Classic Waffle (Seasonal Fruits & Chocolate)', 250],
                ['Dondurmalı Waffle', 'Waffle with Ice Cream', 280],
                ['Fırın Sütlaç', 'Baked Rice Pudding', 120],
                ['Magnolia (Muz, Çilek veya Oreo)', 'Magnolia (Banana, Strawberry or Oreo)', 140],
                ['Profiterol / Tiramisu', 'Profiteroles / Tiramisu', 140],
                ['Tatlı / Tuzlu Kurabiye Tabağı', 'Sweet / Savoury Cookie Plate', 120],
                ['Günün Keki (Dilim)', 'Cake of the Day (Slice)', 110],
            ]],
        ];

        $now = now();

        foreach ($menu as $categoryIndex => [$name, $nameEn, $items]) {
            $categoryId = DB::table('menu_categories')->insertGetId([
                'name' => $name,
                'name_en' => $nameEn,
                'sort_order' => $categoryIndex + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('menu_items')->insert(array_map(fn (array $item, int $itemIndex): array => [
                'menu_category_id' => $categoryId,
                'name' => $item[0],
                'name_en' => $item[1],
                'price' => $item[2],
                'sort_order' => $itemIndex + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ], $items, array_keys($items)));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_categories');
    }
};
