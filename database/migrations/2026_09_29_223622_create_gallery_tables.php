<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Galeri Kategorileri
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 2. Galeri Resimleri
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_category_id')->constrained('gallery_categories')->cascadeOnDelete();
            $table->text('image_path');
            $table->string('tag')->nullable(); // Fotoğrafın sol altındaki etiket (TR)
            $table->string('tag_en')->nullable(); // Fotoğrafın sol altındaki etiket (EN)
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 3. Site settings tablosuna başlıklar (text türünde row limitini aşmamak için)
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('gallery_eyebrow')->nullable()->after('events_subtitle_en');
            $table->text('gallery_title')->nullable()->after('gallery_eyebrow');
            $table->text('gallery_eyebrow_en')->nullable()->after('gallery_title');
            $table->text('gallery_title_en')->nullable()->after('gallery_eyebrow_en');
        });

        // 4. Varsayılan başlıklar
        DB::table('site_settings')->where('id', 1)->update([
            'gallery_eyebrow' => 'Görsel Arşiv',
            'gallery_title' => 'Atmosferik Anlar',
            'gallery_eyebrow_en' => 'Visual Archive',
            'gallery_title_en' => 'Atmospheric Moments',
        ]);

        // 5. Varsayılan Kategoriler
        $catSuites = DB::table('gallery_categories')->insertGetId([
            'name' => 'Süitler (Odalar)',
            'name_en' => 'Suites & Rooms',
            'slug' => 'suites',
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catGastro = DB::table('gallery_categories')->insertGetId([
            'name' => 'Gastronomi',
            'name_en' => 'Gastronomy',
            'slug' => 'gastronomy',
            'sort_order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catWellness = DB::table('gallery_categories')->insertGetId([
            'name' => 'Sağlık',
            'name_en' => 'Wellness',
            'slug' => 'wellness',
            'sort_order' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catGrounds = DB::table('gallery_categories')->insertGetId([
            'name' => 'Bahçe & Plaj',
            'name_en' => 'Grounds & Cove',
            'slug' => 'grounds',
            'sort_order' => 4,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Mevcut 6 görsel ve etiketleri
        $images = [
            [
                'gallery_category_id' => $catSuites,
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBBpg3vWASjF8tnRUMOrOM_wceviMPekiS8-YkmiAEfu3l72-2IjkvEi7ufTclXyLv0Rz_pmE-QHUa4bkU-XNLseExaZ3gH6oOcuPdQkEn9_3ytpEwm7EyaN-ayq57fH1KsJ2P87yh5foYP2oRCR_VSfBb69y-f0TfoVQr5Q_HErWQ99h-LlCA5IiXphPxpF3pCPHtqnWIBruXwv0DXm1lKIjUq-IVJBki0k2m7SkHXLhvYbOvRXDDaRA',
                'tag' => 'Sonsuzluk Tuzlu Su Ufku · 07:45',
                'tag_en' => 'Infinity Saltwater Horizon · 07:45',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gallery_category_id' => $catGastro,
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDO4VluFTU7N_sfuWgNaOsfQrdP0mpgXXdukWFyQenFGJFw7YigKLzKO3Ho3-ezEYnLNPRv0D3csfZN5EmKTSffua4uU4PTrYuGFRg4aPZiO9dQ92V-zV-Tu2WEuymgsAnVJwubHuaiEfFL2xNuyCAQNhCulS9zD7Ot34cHX2jNlR3BGMCbQDppLJRkBEUKrdxx5FO52BZQr92bwWEDJRUWYso9_iLPWMpTngNxXfQ6DoRoRiMJb9CMtw',
                'tag' => 'Asteria · Kıyı Gastronomisi',
                'tag_en' => 'Asteria · Coastal Gastronomy',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gallery_category_id' => $catSuites,
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD1bzmgnrftnYCoQNdZlvrRvFoMyx9gHXy_RZsV5GKfX_lIEOVEaPLl4qx9obl8p1i8qCY6ejkaM50quuinNSDZAZ_hNuOOg2F_gZI3EVwI3Ex5Ghz9ZsCA2tgPwQLGNgid2ZYug2v9GoHwOUXNfaK3aA5qOoggE_SHXiOc-1VWhETDnNmSBJU1OqqUZNP9B8XW-wZEC_N3-nxcQr2sm5ru5BOQklKCtT0CPpOLuIepEi4GzcSrdbxFAQ',
                'tag' => 'Banyo Köşkü · Süit 04',
                'tag_en' => 'The Bathing Pavilion · Suite 04',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gallery_category_id' => $catGastro,
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBGBBH8VdNB7qvHsr44_8sRWDQypoUK2EokNOfitxTDQqrhYCnub4XIg1yTB3X6s1nTHSuj9oe7c71d8aqUsQh3mG6kroD18iKDo3gLFconAp9gTf1gB9bFwXpXbbC-hbqF1bOAjZFXD9hUd88D5cUsxQOfT1fWULmJFm81wZV4EZEPnifc728KBPD1fApXmOeOXN1OZT3-XUJl1zVGuwwxLwetkB2D2nrGb3y-LDpE_b8iPzIGMoGGtQ',
                'tag' => 'Alacakaranlık Köşkü & Ateş Çukuru',
                'tag_en' => 'Dusk Pavilion & Fire Pit',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gallery_category_id' => $catWellness,
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDt2JuF__vDg2bluUqNwTH6hswKGhKcn6z8-E897UoKIhhEQAY0EiOL1VwgckKHEigHjOM4aZh9Km1W2y7jrbRjcSJN4CqszY4SEYU27g4Xc2IeQtemi0Yz57issM77CVdQrZ5WT3_a8q0psLrjrxRYivxFXdZ3kJWVAEOYXpd5R5qRV2hzsumgr6qrN1a59EMjJZegRBWklqLOLrxN1eT0sHuFGOpf_6Tx2N0HYty_rg4ZguVTzDhQgg',
                'tag' => 'Thalasso Eczanesi',
                'tag_en' => 'Thalasso Apothecary',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gallery_category_id' => $catGrounds,
                'image_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBjE39Zqy2A0jx0mLUg-GFVwdhbpiCqKMg5Vd5BXwWP-EmGa2yu-7af9SQQfEXdXjI0EsyG3ULydZ0sXIez-cvthXuU6WhNvOw22EeGv9hJMpzxXFwNYGAHvFfVKtykqV4lF33dzZLE0yChRAx3bUEiF0sShiG78a4dEfaY0AmXVK5-EhUkopvzuQ14bZ-oeBcpkYCTbCABPi2Tk8S_Fe0lcqayvmVc8smmoDwdUZLJwjodnCv8fqFwWA',
                'tag' => 'Gizli Sahil Koyu',
                'tag_en' => 'The Secluded Beach Cove',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('gallery_images')->insert($images);
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('gallery_categories');

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'gallery_eyebrow',
                'gallery_title',
                'gallery_eyebrow_en',
                'gallery_title_en',
            ]);
        });
    }
};
