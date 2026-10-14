<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Reservation Guarantees tablosu
        if (! Schema::hasTable('reservation_guarantees')) {
            Schema::create('reservation_guarantees', function (Blueprint $table) {
                $table->id();
                $table->string('icon')->default('verified');
                $table->string('title');
                $table->string('title_en')->nullable();
                $table->text('description')->nullable();
                $table->text('description_en')->nullable();
                $table->unsignedInteger('sort_order')->default(0)->index();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        // 2. Site settings tablosuna bölüm başlıkları (InnoDB 65535 byte row limitini aşmamak için TEXT tipi kullanılır)
        try {
            DB::statement('ALTER TABLE site_settings ROW_FORMAT=DYNAMIC');
        } catch (Throwable) {
        }

        try {
            if (Schema::hasColumn('site_settings', 'guarantees_eyebrow')) {
                DB::statement('ALTER TABLE site_settings MODIFY COLUMN guarantees_eyebrow TEXT NULL');
            }
            if (Schema::hasColumn('site_settings', 'guarantees_title')) {
                DB::statement('ALTER TABLE site_settings MODIFY COLUMN guarantees_title TEXT NULL');
            }
        } catch (Throwable) {
        }

        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'guarantees_eyebrow')) {
                $table->text('guarantees_eyebrow')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'guarantees_title')) {
                $table->text('guarantees_title')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'guarantees_eyebrow_en')) {
                $table->text('guarantees_eyebrow_en')->nullable();
            }
            if (! Schema::hasColumn('site_settings', 'guarantees_title_en')) {
                $table->text('guarantees_title_en')->nullable();
            }
        });

        // 3. Varsayılan başlıklar
        $defaults = [
            'guarantees_eyebrow' => 'AURA Taahhüdü',
            'guarantees_title' => 'Doğrudan Rezervasyon İçerikleri',
            'guarantees_eyebrow_en' => 'The Aura Undertaking',
            'guarantees_title_en' => 'Direct Reservation Inclusions',
        ];
        $updateData = [];
        foreach ($defaults as $col => $val) {
            if (Schema::hasColumn('site_settings', $col)) {
                $updateData[$col] = $val;
            }
        }
        if (! empty($updateData) && DB::table('site_settings')->where('id', 1)->exists()) {
            DB::table('site_settings')->where('id', 1)->update($updateData);
        }

        // 4. Varsayılan 4 içerik
        $guarantees = [
            [
                'icon' => 'format_image_left',
                'title' => 'Doğrudan Fiyat Güvencesi',
                'title_en' => 'Direct Rate Assurance',
                'description' => 'Varışta doğrudan yarımada ayrıcalığı ile dünya genelinde garantili en düşük ücret.',
                'description_en' => 'Guaranteed lowest rate worldwide with direct peninsula privilege upon arrival.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'price_check',
                'title' => 'Şeffaf Hesap',
                'title_en' => 'Transparent Folio',
                'description' => 'Gizli tesis ücretleri, varış yeri ek ücretleri veya ayrılışta açıklanmamış enerji ekleri bulunmamaktadır.',
                'description_en' => 'No hidden resort fees, destination surcharges, or undisclosed energy supplements upon departure.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'support_agent',
                'title' => '7/24 Yarımada Ev Sahibi',
                'title_en' => '24/7 Peninsula Host',
                'description' => 'Konaklamanızdan önce, süresince ve sonrasında Bodrum concierge masasıyla kesintisiz doğrudan iletişim.',
                'description_en' => 'Uninterrupted direct liaison with our Bodrum concierge desk before, during, and after your stay.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'event_repeat',
                'title' => 'Esnek Depozito Politikası',
                'title_en' => 'Flexible Deposit Policy',
                'description' => 'Mütevazı bir ön ödemeli rezervasyon; bakiye tesise varışta ödenir.',
                'description_en' => 'Secure your reservation with a modest prepaid retainer; balance settled upon arrival at the estate.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        if (DB::table('reservation_guarantees')->count() === 0) {
            DB::table('reservation_guarantees')->insert($guarantees);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_guarantees');

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'guarantees_eyebrow',
                'guarantees_title',
                'guarantees_eyebrow_en',
                'guarantees_title_en',
            ]);
        });
    }
};
