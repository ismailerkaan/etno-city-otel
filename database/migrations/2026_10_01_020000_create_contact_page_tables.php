<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. İletişim Formu Mesajları Tablosu
        if (! Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('email', 150);
                $table->string('phone', 50)->nullable();
                $table->string('subject', 200)->nullable();
                $table->text('message');
                $table->boolean('is_read')->default(false)->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Site settings tablosuna iletişim sayfası alanları (Row size limitini aşmamak için TEXT tipi kullanılır)
        try {
            DB::statement('ALTER TABLE site_settings ROW_FORMAT=DYNAMIC');
        } catch (Throwable) {
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $columns = [
                'contact_eyebrow',
                'contact_title',
                'contact_subtitle',
                'contact_eyebrow_en',
                'contact_title_en',
                'contact_subtitle_en',
                'contact_concierge_hours',
                'contact_concierge_hours_en',
                'contact_phone_concierge',
                'contact_email_reservations',
            ];

            foreach ($columns as $col) {
                if (! Schema::hasColumn('site_settings', $col)) {
                    $table->text($col)->nullable();
                } else {
                    // Mevcutsa da TEXT tipine dönüştürerek satır boyutundan tasarruf et
                    try {
                        DB::statement("ALTER TABLE site_settings MODIFY COLUMN `{$col}` TEXT NULL");
                    } catch (Throwable) {
                    }
                }
            }
        });

        // 3. Varsayılan iletişim sayfası başlıkları
        $defaults = [
            'contact_eyebrow' => 'İletişim & Rezervasyon Masası',
            'contact_title' => 'Yarımadanın Kalbinde Sizin İçin Buradayız',
            'contact_subtitle' => 'Özel konaklama talepleriniz, helikopter ve yat transferleri, özel şef rezervasyonları veya etkinlik planlarınız için 7/24 hizmetinizdeyiz.',
            'contact_eyebrow_en' => 'Contact & Peninsula Concierge',
            'contact_title_en' => 'We Are Here For You at Cape Artemis',
            'contact_subtitle_en' => 'Dedicated Peninsula hosts available 24/7 for bespoke accommodations, private helicopter or tender transfers, and curated itineraries.',
            'contact_concierge_hours' => '7/24 Kesintisiz Concierge & Rezervasyon Masası',
            'contact_concierge_hours_en' => '24/7 Dedicated Peninsula Concierge & Host',
            'location_title' => 'Konumumuz',
            'location_title_en' => 'Our Location',
            'location_description' => 'Yol tarifi alarak otelimize kolayca ulaşabilirsiniz.',
            'location_description_en' => 'You can easily reach our hotel by getting directions.',
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
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_eyebrow',
                'contact_title',
                'contact_subtitle',
                'contact_eyebrow_en',
                'contact_title_en',
                'contact_subtitle_en',
                'contact_concierge_hours',
                'contact_concierge_hours_en',
                'contact_phone_concierge',
                'contact_email_reservations',
            ]);
        });
    }
};
