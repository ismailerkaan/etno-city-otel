<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Events Tablosu
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->text('image_path')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('category_en', 100)->nullable();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->string('button_text', 100)->default('Deneyimi Rezerve Et');
            $table->string('button_text_en', 100)->default('Reserve Journey');
            $table->string('button_url', 500)->default('#');
            $table->boolean('show_on_home')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        // 2. Site settings tablosuna bölüm başlıkları (text kullanarak row size limitini aşıyoruz)
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('events_eyebrow')->nullable()->after('amenities_subtitle_en');
            $table->text('events_title')->nullable()->after('events_eyebrow');
            $table->text('events_subtitle')->nullable()->after('events_title');
            $table->text('events_eyebrow_en')->nullable()->after('events_subtitle');
            $table->text('events_title_en')->nullable()->after('events_eyebrow_en');
            $table->text('events_subtitle_en')->nullable()->after('events_title_en');
        });

        // 3. Varsayılan başlıklar
        DB::table('site_settings')->where('id', 1)->update([
            'events_eyebrow' => 'Villanın Ötesinde',
            'events_title' => 'Özel Deneyimler',
            'events_subtitle' => 'Her deneyim özel olarak tasarlanmış, aceleye getirilmeden ve değişen gün ışığıyla senkronize edilmiştir.',
            'events_eyebrow_en' => 'Beyond the Villa',
            'events_title_en' => 'Bespoke Chapters',
            'events_subtitle_en' => 'Each experience is privately curated, unhurried, and calibrated to shifting daylight.',
        ]);

        // 4. Varsayılan 3 etkinlik
        $events = [
            [
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCozr55xFai5XKoLtJMktkX8F9RaGIXmfGwEH2rjel-FQcgVqqfPvQnV0vt5H34M1FKXfk4fyThLxHjiXLLR6Mfkvc_5iOBaK3ZG1CWxKMRTn6x6clByIMbAN84e8CRzqbNXRbxMLQvSBGI8_u3plpirD4Uv9kgX6IrVvtApEmTs4_jDIsTh4tI6_i36w0Jyo5_i4HNKahp3sgYrbU6oUaL1KFhFST2aEJFBW_YScW-Jqk5pqB-F_SLnw',
                'category' => 'Gastronomi Deneyimi',
                'category_en' => 'Gastronomic Intimacy',
                'title' => 'Uçurum Kenarında Mumlu Akşam Yemeği',
                'title_en' => 'Candlelit Cliffside Dining',
                'description' => "Cape Artemis'in çarplayan dalgaları üzerinde, yabani zeytin odunu közünde pişirilen özel 7 kurslu deniz ürünleri menüsü.",
                'description_en' => 'A private 7-course seafood feast seared over wild olive embers, set above the crashing surf of Cape Artemis.',
                'button_text' => 'Deneyimi Rezerve Et',
                'button_text_en' => 'Reserve Journey',
                'button_url' => '#',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC8vuCl91EaokGGxRi-REoC_j1dSA1G6usdzuTX4Lvg_la0juE118rNYmnmytbeO5b8pgzm6EakiT2SWPTXWvHu1l4i0OPF2XJL_qqOG3_MzGzj9NXeMos5n-N61ClTEHD16-EASj-B3CBK7w9KUKL6n4smgbVLTZ2QekaOnxb9OIKoZfx89dkfav2c1CCAeJSH2Ji9Ll77vWBfuAtpAqxEaGQ4DtB8ffu5wrqW8Gw5GKmuH3wYTJ6iPw',
                'category' => 'Denizcilik Keşfi',
                'category_en' => 'Maritime Discovery',
                'title' => 'Ege Katamaran Turu',
                'title_en' => 'Aegean Catamaran Sailing',
                'description' => 'Soğuk vintage Şampanya eşliğinde gizli volkanik koyları ve deniz mağaralarını keşfetmek için güneş enerjili katamaran yatımızı özel olarak kiralayın.',
                'description_en' => 'Privately charter our solar-assisted catamaran to explore secluded volcanic coves and sea caves with chilled vintage Champagne.',
                'button_text' => 'Deneyimi Rezerve Et',
                'button_text_en' => 'Reserve Journey',
                'button_url' => '#',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBnqd4PLQ7KD7retgEUK6pHz3wEK74irh4kspq_YrxpxVgU7IaTi1KM3WuB0p-E0WG8qD4reNmPB5peoQT9ev2aeGZYuZgR4mJpv275ltaErQITYGiOXPwGC0s3bXPVc7fLRBOC_1pCoGzY1rUcTBnEaAF-rVTpvZa3hIBJMPXpkOzrwYWCGPLvhtE7TyDxMKL5IMuQP1hbsKL511tlM2ieJMmTVZ7zc1inOXXLybMjrqk0gOpvZgeGcg',
                'category' => 'Duyusal Uyanış',
                'category_en' => 'Sensory Awakening',
                'title' => 'Ses & Botanik Spa',
                'title_en' => 'Sound & Botanical Spa',
                'description' => 'Yerli yabani mersin, menengiç reçinesi ve sıcak kıyı deniz tuzu kompreslerle uyumlu vibrasyon frekansı tasları.',
                'description_en' => 'Vibrational frequency bowls tuned to endemic wild myrtle, mastic resin, and warm coastal sea-salt compresses.',
                'button_text' => 'Deneyimi Rezerve Et',
                'button_text_en' => 'Reserve Journey',
                'button_url' => '#',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('events')->insert($events);
    }

    public function down(): void
    {
        Schema::dropIfExists('events');

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'events_eyebrow',
                'events_title',
                'events_subtitle',
                'events_eyebrow_en',
                'events_title_en',
                'events_subtitle_en',
            ]);
        });
    }
};
