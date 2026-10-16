<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            DB::table('site_settings')->where('id', 1)->update([
                'events_eyebrow' => 'EtnoCity Organizasyon',
                'events_title' => 'Şehrin Merkezinde Kusursuz Organizasyonlar',
                'events_subtitle' => 'Düğünlerden iş toplantılarına, kurumsal buluşmalardan özel günlere kadar her organizasyonu deneyimli ekibimiz, esnek salon düzenleri ve özenli servis anlayışıyla planlıyor.',
                'events_eyebrow_en' => 'EtnoCity Events',
                'events_title_en' => 'Seamless Events in the Heart of the City',
                'events_subtitle_en' => 'From weddings and business meetings to corporate gatherings and special occasions, our experienced team brings every detail together with flexible spaces and attentive service.',
                'updated_at' => now(),
            ]);

            DB::table('events')->delete();
            DB::table('events')->insert($this->cityHotelEvents());
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            DB::table('site_settings')->where('id', 1)->update([
                'events_eyebrow' => 'Villanın Ötesinde',
                'events_title' => 'Özel Deneyimler',
                'events_subtitle' => 'Her deneyim özel olarak tasarlanmış, aceleye getirilmeden ve değişen gün ışığıyla senkronize edilmiştir.',
                'events_eyebrow_en' => 'Beyond the Villa',
                'events_title_en' => 'Bespoke Chapters',
                'events_subtitle_en' => 'Each experience is privately curated, unhurried, and calibrated to shifting daylight.',
                'updated_at' => now(),
            ]);

            DB::table('events')->delete();
            DB::table('events')->insert($this->legacyEvents());
        });
    }

    /** @return array<int, array<string, mixed>> */
    private function cityHotelEvents(): array
    {
        $timestamp = now();

        return [
            [
                'image_path' => 'events/wedding-organization.jpg',
                'category' => 'Düğün & Davet',
                'category_en' => 'Weddings & Receptions',
                'title' => 'Düğün Organizasyonları',
                'title_en' => 'Wedding Organizations',
                'description' => 'Nikâh sonrası davetlerden geniş katılımlı düğün yemeklerine kadar salon düzeni, menü seçimi, masa yerleşimi ve servis akışını tek bir plan altında buluşturuyoruz. Çiftlerin beklentilerine göre şekillenen organizasyon sürecinde hazırlıktan son misafirin uğurlanmasına kadar deneyimli ekibimiz yanınızda olur.',
                'description_en' => 'From intimate post-ceremony receptions to larger wedding dinners, we coordinate the room layout, menu selection, table plan and service flow in one seamless plan. Our experienced team supports each couple from the first preparations until the final guest departs.',
                'button_text' => 'Organizasyon Teklifi Al',
                'button_text_en' => 'Request an Event Proposal',
                'button_url' => '/iletisim?subject=Düğün Organizasyonu',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'image_path' => 'events/meeting-organization.jpg',
                'category' => 'Toplantı & Eğitim',
                'category_en' => 'Meetings & Training',
                'title' => 'Toplantı Organizasyonları',
                'title_en' => 'Meeting Organizations',
                'description' => 'Yönetim toplantıları, eğitimler, sunumlar ve ekip buluşmaları için katılımcı sayınıza uygun salon düzenleri hazırlıyoruz. Yüksek hızlı internet, görüntü ve ses sistemleri, ikram seçenekleri ve ihtiyaç duyulan teknik destek organizasyon planına dahil edilir.',
                'description_en' => 'We prepare flexible room layouts for board meetings, training sessions, presentations and team gatherings. High-speed internet, audio-visual systems, refreshment options and technical support can all be incorporated into your event plan.',
                'button_text' => 'Toplantınızı Planlayın',
                'button_text_en' => 'Plan Your Meeting',
                'button_url' => '/iletisim?subject=Toplantı Organizasyonu',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'image_path' => 'events/corporate-events.jpg',
                'category' => 'Kurumsal Organizasyon',
                'category_en' => 'Corporate Events',
                'title' => 'Kurumsal Etkinlikler',
                'title_en' => 'Corporate Events',
                'description' => 'Lansman, seminer, bayi toplantısı, şirket yemeği ve yıl sonu buluşmaları için markanızın ihtiyaçlarına uygun çözümler sunuyoruz. Karşılama, sahne ve oturma düzeni, teknik altyapı, yiyecek-içecek servisi ve konaklama talepleri tek ekip tarafından koordine edilir.',
                'description_en' => 'We deliver tailored solutions for product launches, seminars, dealer meetings, company dinners and year-end gatherings. Guest welcome, staging, seating, technical infrastructure, catering and accommodation requirements are coordinated by one dedicated team.',
                'button_text' => 'Kurumsal Teklif Al',
                'button_text_en' => 'Request a Corporate Proposal',
                'button_url' => '/iletisim?subject=Kurumsal Etkinlik',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'image_path' => 'events/group-dining.jpg',
                'category' => 'Yeme & İçme',
                'category_en' => 'Group Dining',
                'title' => 'Toplu Yemek Organizasyonları',
                'title_en' => 'Group Dining Organizations',
                'description' => 'İş yemekleri, dernek buluşmaları, aile davetleri ve kalabalık gruplar için kişi sayısına ve etkinlik akışına uygun menüler hazırlıyoruz. Oturma planından servis zamanlamasına kadar tüm ayrıntılar mutfak ve organizasyon ekiplerimiz tarafından birlikte yönetilir.',
                'description_en' => 'We create menus suited to the guest count and event flow for business dinners, association gatherings, family occasions and larger groups. From the seating plan to service timing, every detail is managed jointly by our culinary and events teams.',
                'button_text' => 'Menü ve Teklif Al',
                'button_text_en' => 'Request Menu & Proposal',
                'button_url' => '/iletisim?subject=Toplu Yemek Organizasyonu',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'image_path' => 'events/special-days.jpg',
                'category' => 'Kutlama & Anma',
                'category_en' => 'Celebrations & Commemorations',
                'title' => 'Özel Gün ve Haftalara Ait Organizasyonlar',
                'title_en' => 'Special Day and Week Events',
                'description' => 'Doğum günü, nişan, mezuniyet, yıl dönümü ve özel gün veya haftalara yönelik yemekli ya da kokteyl düzeninde organizasyonlar hazırlıyoruz. Konsepte uygun salon düzeni, menü, karşılama ve servis planı taleplerinize göre kişiselleştirilir.',
                'description_en' => 'We organize seated dinners or cocktail-style events for birthdays, engagements, graduations, anniversaries and special observance days or weeks. The room setup, menu, guest welcome and service plan are personalized to suit your concept.',
                'button_text' => 'Organizasyonu Planlayın',
                'button_text_en' => 'Plan Your Event',
                'button_url' => '/iletisim?subject=Özel Gün Organizasyonu',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function legacyEvents(): array
    {
        $timestamp = now();

        return [
            [
                'image_path' => null,
                'category' => 'Gastronomi Deneyimi',
                'category_en' => 'Gastronomic Intimacy',
                'title' => 'Uçurum Kenarında Mumlu Akşam Yemeği',
                'title_en' => 'Candlelit Cliffside Dining',
                'description' => 'Özel akşam yemeği deneyimi.',
                'description_en' => 'A private dining experience.',
                'button_text' => 'Deneyimi Rezerve Et',
                'button_text_en' => 'Reserve Journey',
                'button_url' => '#',
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];
    }
};
