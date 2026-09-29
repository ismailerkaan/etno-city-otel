<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Amenities tablosu
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('spa');
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 2. Site settings tablosuna bölüm başlıkları
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('amenities_eyebrow')->nullable()->after('award_badge_subtitle_en');
            $table->string('amenities_title')->nullable()->after('amenities_eyebrow');
            $table->text('amenities_subtitle')->nullable()->after('amenities_title');
            $table->string('amenities_eyebrow_en')->nullable()->after('amenities_subtitle');
            $table->string('amenities_title_en')->nullable()->after('amenities_eyebrow_en');
            $table->text('amenities_subtitle_en')->nullable()->after('amenities_title_en');
        });

        // 3. Varsayılan başlıklar
        DB::table('site_settings')->where('id', 1)->update([
            'amenities_eyebrow' => 'Sessiz Olanaklar',
            'amenities_title' => 'Mimari & Özen',
            'amenities_subtitle' => 'Mülkün her boyutu, sakin fiziksel rahatlamayı ve derin, yenileyici uykuyu teşvik etmek için tasarlanmıştır.',
            'amenities_eyebrow_en' => 'Quiet Facilities',
            'amenities_title_en' => 'Curated Architecture & Care',
            'amenities_subtitle_en' => 'Every dimension of the property is curated to encourage quiet physical decompression and deep, restorative sleep.',
        ]);

        // 4. Varsayılan 8 olanak
        $amenities = [
            [
                'icon' => 'pool',
                'title' => 'Sonsuzluk Tuzlu Su Havuzu',
                'title_en' => 'Infinity Saltwater Pool',
                'description' => 'Ege kıyısının 40 metre üzerinde asılı, Akdeniz volkanik kaya filtrasyonuyla doğal olarak arıtılmıştır.',
                'description_en' => 'Suspended 40 meters above the Aegean surf, naturally purified with Mediterranean volcanic rock filtration.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'spa',
                'title' => 'Thalasso Spa & Hamamlar',
                'title_en' => 'Thalasso Spa & Baths',
                'description' => 'Klasik Anadolu sağlık anlayışından ilham alan termal deniz hamamları, soğuk dalış sarnıçları ve özel yosun biyoritual uygulamaları.',
                'description_en' => 'Thermal marine baths, cold plunge cisterns, and bespoke seaweed bioritual therapies inspired by classical Anatolian wellness.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'restaurant_menu',
                'title' => 'Asteria Gurme Restoran',
                'title_en' => 'Asteria Fine Dining',
                'description' => 'Mevsimlik, tarladan sofraya, yarımadadan toplanan botanik yağlar kullanılarak Baş Aşçı Murat Erdem yönetimindeki kıyı gastronomisi.',
                'description_en' => 'Hyper-seasonal, hyper-local coastal gastronomy using peninsula-foraged botanicals under Chef Murat Erdem.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'beach_access',
                'title' => 'Gizli Özel Plaj',
                'title_en' => 'Secluded Private Beach',
                'description' => 'Altı gölgeli kanvas kabana, soğuk oshibori servisi ve kano keşifleri ile sınırlı kristal koy erişimi.',
                'description_en' => 'Limited crystal cove access with six shaded canvas cabanas, chilled oshibori service, and kayak exploration.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'flight_takeoff',
                'title' => 'Helipad & Yat Transferi',
                'title_en' => 'Helipad & Yacht Transfer',
                'description' => 'Özel 48 ft Pardo yat servisimizle tamamlanan, güney sırtımızdaki doğrudan helikopter iniş koordinatı.',
                'description_en' => 'Direct helicopter touch-down coordinates on our southern ridge, complemented by our private 48ft Pardo tender.',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'wifi_tethering',
                'title' => 'Starlink Bağlantısı',
                'title_en' => 'Starlink Connectivity',
                'description' => 'Zahmetsiz, kesintisiz uzaktan odaklanma için sığınağın her köşesinde ultra hızlı, gizli uydu Wi-Fi.',
                'description_en' => 'Ultra-high-speed, discreet satellite Wi-Fi woven silently through every corner of the sanctuary for effortless remote focus.',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'room_service',
                'title' => '7/24 Kişiye Özel Oda Servisi',
                'title_en' => '24/7 Bespoke In-Suite',
                'description' => 'Her gece sommelier şarap eşleşmeleri, özel ocak ızgarası ve kişisel ritminize göre ayarlanan şafak vakti taze meyve suyu teslimatları.',
                'description_en' => 'Nightly sommelier cellar pairings, private hearth barbecue, and dawn fresh-juice drops matched to your circadian schedule.',
                'sort_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'electric_car',
                'title' => 'Vale & Elektrikli Araç Şoförü',
                'title_en' => 'Valet & EV Chauffeur',
                'description' => 'Özel rehberler eşliğinde kıyı keşfi için talep üzerine sunulan sessiz elektrikli Porsche Taycan ve Range Rover filosu.',
                'description_en' => 'A fleet of silent electric Porsche Taycans and Range Rovers on standby for coastal exploration with private escorts.',
                'sort_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('amenities')->insert($amenities);
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'amenities_eyebrow',
                'amenities_title',
                'amenities_subtitle',
                'amenities_eyebrow_en',
                'amenities_title_en',
                'amenities_subtitle_en',
            ]);
        });
    }
};
