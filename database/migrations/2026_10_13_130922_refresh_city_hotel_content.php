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
            'about_eyebrow' => 'EtnoCity Otel’e Hoş Geldiniz',
            'about_eyebrow_en' => 'Welcome to EtnoCity Hotel',
            'about_title' => 'Şehrin Ritmine Uyum Sağlayan Konfor',
            'about_title_en' => 'Comfort That Keeps Pace With the City',
            'about_primary_text' => 'EtnoCity Otel; iş seyahatlerinden kısa hafta sonu ziyaretlerine kadar farklı konaklama ihtiyaçlarını, sade tasarım ve özenli hizmet anlayışıyla bir araya getirir. Günün temposunun ardından dinlenebileceğiniz odalarımızda ihtiyaç duyduğunuz her ayrıntı elinizin altındadır.',
            'about_primary_text_en' => 'EtnoCity Hotel brings together thoughtful service and contemporary design for business trips, short breaks and everyday travel. Our rooms provide a calm, practical place to recharge after a busy day.',
            'about_secondary_text' => 'Kolay ulaşım, güçlü internet bağlantısı, rahat çalışma alanları ve güne iyi başlamanızı sağlayan kahvaltı seçenekleriyle konaklamanızı zahmetsiz hale getiriyoruz.',
            'about_secondary_text_en' => 'Convenient transport links, reliable Wi-Fi, comfortable work areas and a satisfying breakfast make every stay straightforward.',
            'award_badge_title' => 'Konforlu ve Özenli Konaklama',
            'award_badge_subtitle' => 'İş ve şehir gezileri için işlevsel çözümler',
            'award_badge_title_en' => 'Comfort With Thoughtful Service',
            'award_badge_subtitle_en' => 'Practical stays for business and city breaks',
            'amenities_eyebrow' => 'Konaklamanızı Kolaylaştıranlar',
            'amenities_title' => 'İhtiyacınız Olan Her Şey',
            'amenities_subtitle' => 'Dinlenmek, çalışmak ve güne hazır başlamak için gerekli hizmetleri bir araya getiriyoruz.',
            'amenities_eyebrow_en' => 'Everything for an Easy Stay',
            'amenities_title_en' => 'The Essentials, Thoughtfully Provided',
            'amenities_subtitle_en' => 'Practical services for resting, working and starting the day well.',
            'gallery_eyebrow' => 'Otelden Kareler',
            'gallery_title' => 'Konaklamanızı Yakından Görün',
            'gallery_description' => 'Odalarımızı, ortak alanlarımızı ve konaklamanızı tamamlayan ayrıntıları keşfedin.',
            'gallery_eyebrow_en' => 'Around the Hotel',
            'gallery_title_en' => 'Take a Closer Look at Your Stay',
            'gallery_description_en' => 'Explore our rooms, shared spaces and the details that complete your stay.',
            'testimonials_eyebrow' => 'Misafir Yorumları',
            'testimonials_title' => 'Konaklayanların Gözünden',
            'testimonials_description' => 'İş, aile ve kısa süreli şehir ziyaretleri için bizi tercih eden misafirlerimizin deneyimleri.',
            'testimonials_eyebrow_en' => 'Guest Reviews',
            'testimonials_title_en' => 'In Our Guests’ Words',
            'testimonials_description_en' => 'Experiences shared by guests visiting for business, family trips and short city breaks.',
            'location_eyebrow' => 'Ulaşım',
            'location_title' => 'Şehrin Önemli Noktalarına Kolay Erişim',
            'location_description' => 'İş merkezlerine, alışveriş noktalarına ve şehir içi ulaşım bağlantılarına rahatça erişebileceğiniz bir konumdayız.',
            'location_eyebrow_en' => 'Getting Here',
            'location_title_en' => 'Easy Access to the City',
            'location_description_en' => 'A convenient base with straightforward access to business districts, shopping and public transport.',
            'location_address' => 'Şehir Merkezi',
            'address' => 'Şehir Merkezi',
            'newsletter_title' => 'EtnoCity’den Haberler',
            'newsletter_title_en' => 'News from EtnoCity',
            'newsletter_text' => 'Konaklama fırsatları, otel duyuruları ve güncel bilgileri e-posta kutunuzda alın.',
            'newsletter_text_en' => 'Receive stay offers, hotel news and useful updates in your inbox.',
            'guarantees_eyebrow' => 'Doğrudan Rezervasyon',
            'guarantees_title' => 'Açık, Kolay ve Güvenilir',
            'guarantees_eyebrow_en' => 'Book Direct',
            'guarantees_title_en' => 'Clear, Simple and Reliable',
            'contact_eyebrow' => 'İletişim & Rezervasyon',
            'contact_title' => 'Konaklamanız İçin Buradayız',
            'contact_subtitle' => 'Rezervasyon, oda tercihleri, ulaşım ve konaklamanıza ilişkin tüm sorularınız için ekibimize ulaşabilirsiniz.',
            'contact_eyebrow_en' => 'Contact & Reservations',
            'contact_title_en' => 'Here to Help With Your Stay',
            'contact_subtitle_en' => 'Contact our team for reservations, room preferences, transport information and any questions about your stay.',
            'contact_concierge_hours' => '7/24 Resepsiyon Desteği',
            'contact_concierge_hours_en' => '24/7 Front Desk Support',
            'updated_at' => now(),
        ]);

        $amenities = [
            ['support_agent', '7/24 Resepsiyon', '24/7 Front Desk', 'Günün her saatinde karşılama, bilgi ve konaklama desteği.', 'A welcoming team ready to assist at any hour.'],
            ['wifi', 'Yüksek Hızlı Wi-Fi', 'High-Speed Wi-Fi', 'Odalar ve ortak alanlarda kesintisiz internet bağlantısı.', 'Reliable internet access in rooms and shared spaces.'],
            ['restaurant', 'Kahvaltı', 'Breakfast', 'Güne taze ve dengeli seçeneklerle başlayabileceğiniz kahvaltı servisi.', 'A fresh, balanced breakfast to start the day well.'],
            ['desk', 'Çalışma Alanları', 'Work-Friendly Spaces', 'İşlerinizi rahatça sürdürebileceğiniz kullanışlı alanlar.', 'Practical spaces where you can work comfortably.'],
            ['cleaning_services', 'Günlük Kat Hizmeti', 'Daily Housekeeping', 'Temiz ve düzenli bir konaklama için günlük oda bakımı.', 'Daily room care for a clean and comfortable stay.'],
            ['luggage', 'Bagaj Emaneti', 'Luggage Storage', 'Giriş öncesi ve çıkış sonrasında bagajlarınız için güvenli alan.', 'Secure storage before check-in and after check-out.'],
            ['local_laundry_service', 'Çamaşır & Ütü Hizmeti', 'Laundry & Ironing', 'İş ve uzun süreli konaklamalar için pratik bakım desteği.', 'Convenient garment care for business and longer stays.'],
            ['local_taxi', 'Ulaşım Desteği', 'Transport Assistance', 'Taksi ve şehir içi ulaşım seçenekleri için resepsiyon desteği.', 'Front desk assistance with taxis and local transport.'],
        ];

        foreach (DB::table('amenities')->orderBy('sort_order')->pluck('id')->values() as $index => $id) {
            if (isset($amenities[$index])) {
                [$icon, $title, $titleEn, $description, $descriptionEn] = $amenities[$index];
                DB::table('amenities')->where('id', $id)->update([
                    'icon' => $icon,
                    'title' => $title,
                    'title_en' => $titleEn,
                    'description' => $description,
                    'description_en' => $descriptionEn,
                    'updated_at' => now(),
                ]);
            }
        }

        $guarantees = [
            ['verified', 'Doğrudan Fiyat Güvencesi', 'Direct Rate Assurance', 'Rezervasyonunuzu aracı olmadan, açık fiyat bilgisiyle tamamlayın.', 'Book without an intermediary and with clear pricing.'],
            ['receipt_long', 'Şeffaf Ücretlendirme', 'Transparent Pricing', 'Ödeme öncesinde gecelik fiyatı ve toplam tutarı net biçimde görün.', 'See the nightly rate and total amount clearly before payment.'],
            ['support_agent', 'Kolay İletişim', 'Direct Assistance', 'Konaklama öncesinde ve süresince ekibimize doğrudan ulaşın.', 'Reach our team directly before and during your stay.'],
            ['event_repeat', 'Değişiklik Desteği', 'Change Support', 'Planınız değiştiğinde rezervasyon koşullarınız doğrultusunda destek alın.', 'Get help in line with your booking terms when plans change.'],
        ];

        foreach (DB::table('reservation_guarantees')->orderBy('sort_order')->pluck('id')->values() as $index => $id) {
            if (isset($guarantees[$index])) {
                [$icon, $title, $titleEn, $description, $descriptionEn] = $guarantees[$index];
                DB::table('reservation_guarantees')->where('id', $id)->update([
                    'icon' => $icon, 'title' => $title, 'title_en' => $titleEn,
                    'description' => $description, 'description_en' => $descriptionEn, 'updated_at' => now(),
                ]);
            }
        }

        $nearbyPlaces = [
            ['location_city', 'Şehir Merkezi', 'City Centre', 'Alışveriş, yeme içme ve günlük ihtiyaç noktalarına kolay erişim.', 'Easy access to shopping, dining and everyday essentials.', 'Kısa mesafe'],
            ['directions_bus', 'Toplu Taşıma Bağlantıları', 'Public Transport Links', 'Şehrin farklı bölgelerine ulaşım sağlayan bağlantılar yakındadır.', 'Nearby connections make it easy to reach different parts of the city.', 'Kolay erişim'],
            ['business_center', 'İş ve Ticaret Noktaları', 'Business Districts', 'İş görüşmeleri ve kurumsal ziyaretler için elverişli ulaşım.', 'Convenient access for meetings and corporate visits.', 'Merkezi konum'],
        ];

        foreach (DB::table('nearby_places')->orderBy('sort_order')->pluck('id')->values() as $index => $id) {
            if (isset($nearbyPlaces[$index])) {
                [$icon, $title, $titleEn, $description, $descriptionEn, $distance] = $nearbyPlaces[$index];
                DB::table('nearby_places')->where('id', $id)->update([
                    'icon' => $icon, 'title' => $title, 'title_en' => $titleEn,
                    'description' => $description, 'description_en' => $descriptionEn,
                    'distance' => $distance, 'updated_at' => now(),
                ]);
            }
        }

        $testimonials = [
            ['İş Seyahati Misafiri', 'Business Traveller', 'Merkezi konumu ve hızlı interneti sayesinde programımı rahatça yönettim. Oda temiz, sessiz ve çalışmak için oldukça kullanışlıydı.', 'The location and reliable Wi-Fi made my schedule easy to manage. The room was clean, quiet and practical for work.'],
            ['Hafta Sonu Konaklaması', 'Weekend Guest', 'Şehri gezmek için iyi bir başlangıç noktasıydı. Resepsiyon ekibi ulaşım ve çevre önerileri konusunda çok yardımcı oldu.', 'A convenient base for exploring the city. The front desk team was very helpful with transport and local recommendations.'],
            ['Aile Konaklaması', 'Family Stay', 'Aile odası ferah ve düzenliydi. Girişten çıkışa kadar tüm süreç hızlı ve sorunsuz ilerledi.', 'The family room was spacious and well prepared. Everything from check-in to departure was smooth and efficient.'],
        ];

        foreach (DB::table('testimonials')->orderBy('sort_order')->pluck('id')->values() as $index => $id) {
            if (isset($testimonials[$index])) {
                [$authorTitle, $authorTitleEn, $comment, $commentEn] = $testimonials[$index];
                DB::table('testimonials')->where('id', $id)->update([
                    'author_title' => $authorTitle, 'author_title_en' => $authorTitleEn,
                    'comment' => $comment, 'comment_en' => $commentEn, 'updated_at' => now(),
                ]);
            }
        }

        DB::table('gallery_categories')->where('slug', 'suites')->update(['name' => 'Odalar', 'name_en' => 'Rooms', 'updated_at' => now()]);
        DB::table('gallery_categories')->where('slug', 'gastronomy')->update(['name' => 'Kahvaltı & Lezzetler', 'name_en' => 'Breakfast & Dining', 'updated_at' => now()]);
        DB::table('gallery_categories')->where('slug', 'wellness')->update(['name' => 'Ortak Alanlar', 'name_en' => 'Shared Spaces', 'updated_at' => now()]);
        DB::table('gallery_categories')->where('slug', 'grounds')->update(['name' => 'Otel Detayları', 'name_en' => 'Hotel Details', 'updated_at' => now()]);

        $galleryTags = [
            ['Konforlu Oda Detayları', 'Comfortable Room Details'],
            ['Güne İyi Bir Başlangıç', 'A Good Start to the Day'],
            ['Modern Banyo', 'Modern Bathroom'],
            ['Dinlenme Alanları', 'Relaxing Shared Spaces'],
            ['Özenli Ayrıntılar', 'Thoughtful Details'],
            ['EtnoCity’den Kareler', 'Around EtnoCity'],
        ];

        foreach (DB::table('gallery_images')->orderBy('sort_order')->pluck('id')->values() as $index => $id) {
            if (isset($galleryTags[$index])) {
                DB::table('gallery_images')->where('id', $id)->update([
                    'tag' => $galleryTags[$index][0], 'tag_en' => $galleryTags[$index][1], 'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Content migrations are intentionally not reverted to avoid overwriting later editorial changes.
    }
};
