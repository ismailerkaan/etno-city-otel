<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            ['ekonomik', 'Ekonomik', 'Economy', 'Konforlu bir konaklama için ihtiyaç duyulan temel detayları sade ve işlevsel bir tasarımla sunar.', 'A simple and functional room offering every essential detail for a comfortable stay.', 2200, 2, 0, 18, '1 Çift Kişilik Yatak', '1 Double Bed', ['Klima', 'Ücretsiz Wi-Fi', 'Özel Banyo', 'Akıllı TV'], ['Air Conditioning', 'Free Wi-Fi', 'Private Bathroom', 'Smart TV'], ['1566665797739-1674de7a421a', '1560185007-c5ca9d2c014d', '1542314831-068cd1dbfeeb']],
            ['single', 'Single', 'Single', 'Tek kişilik seyahatler için huzurlu, modern ve verimli bir yaşam alanı.', 'A calm, modern and efficient space designed for solo travellers.', 2600, 1, 0, 20, '1 Tek Kişilik Yatak', '1 Single Bed', ['Çalışma Masası', 'Klima', 'Ücretsiz Wi-Fi', 'Minibar'], ['Work Desk', 'Air Conditioning', 'Free Wi-Fi', 'Minibar'], ['1590490360182-c33d57733427', '1618773928121-c32242e63f39', '1551882547-ff40c63fe5fa']],
            ['standart', 'Standart', 'Standard', 'Zarif detaylar, ferah kullanım alanı ve çift kişilik konforu bir araya getirir.', 'Elegant details, generous space and double comfort come together.', 3200, 2, 1, 26, '1 Queen Yatak', '1 Queen Bed', ['Oturma Alanı', 'Klima', 'Ücretsiz Wi-Fi', 'Minibar'], ['Sitting Area', 'Air Conditioning', 'Free Wi-Fi', 'Minibar'], ['1611892440504-42a792e24d32', '1578683010236-d716f9a3f461', '1596394516093-501ba68a0ba6']],
            ['balkonlu-oda', 'Balkonlu Oda', 'Balcony Room', 'Özel balkonuyla günün her saatinde açık havanın keyfini çıkarabileceğiniz ferah bir oda.', 'A spacious room with a private balcony for enjoying the outdoors throughout the day.', 3900, 2, 1, 30, '1 King Yatak', '1 King Bed', ['Özel Balkon', 'Oturma Alanı', 'Klima', 'Minibar'], ['Private Balcony', 'Sitting Area', 'Air Conditioning', 'Minibar'], ['1582719478250-c89cae4dc85b', '1566073771259-6a8506099945', '1584132967334-10e028bd69f7']],
            ['manzarali-oda', 'Manzaralı Oda', 'View Room', 'Geniş pencerelerinden çevrenin dingin manzarasını içeri taşıyan özel konaklama deneyimi.', 'A special stay framed by wide windows and a peaceful surrounding view.', 4700, 2, 1, 32, '1 King Yatak', '1 King Bed', ['Panoramik Manzara', 'Oturma Alanı', 'Klima', 'Kahve Makinesi'], ['Panoramic View', 'Sitting Area', 'Air Conditioning', 'Coffee Machine'], ['1591088398332-8a7791972843', '1598928506311-c55ded91a20c', '1615874694520-474822394e73']],
            ['aile-odasi', 'Aile Odası', 'Family Room', 'Ailelerin birlikte rahatça konaklayabilmesi için geniş alan ve esnek yatak düzeni sunar.', 'Generous space and flexible bedding designed for comfortable family stays.', 5600, 3, 2, 42, '1 King ve 2 Tek Kişilik Yatak', '1 King and 2 Single Beds', ['Aile Oturma Alanı', 'Geniş Banyo', 'Klima', 'Çocuk Yatağı Seçeneği'], ['Family Sitting Area', 'Large Bathroom', 'Air Conditioning', 'Cot Option'], ['1564501049412-61c2a3083791', '1578683010236-d716f9a3f461', '1596394516093-501ba68a0ba6']],
        ];

        foreach ($roomTypes as $sortOrder => $roomData) {
            [$slug, $name, $nameEn, $description, $descriptionEn, $price, $maxAdults, $maxChildren, $size, $bedType, $bedTypeEn, $features, $featuresEn, $imageIds] = $roomData;

            $roomType = RoomType::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name, 'name_en' => $nameEn, 'description' => $description,
                    'description_en' => $descriptionEn, 'nightly_price' => $price, 'currency' => 'TRY',
                    'max_adults' => $maxAdults, 'max_children' => $maxChildren, 'size_sqm' => $size,
                    'bed_type' => $bedType, 'bed_type_en' => $bedTypeEn, 'features' => $features,
                    'features_en' => $featuresEn, 'sort_order' => $sortOrder + 1, 'is_active' => true,
                ],
            );

            foreach ($imageIds as $imageIndex => $imageId) {
                $roomType->images()->updateOrCreate(
                    ['sort_order' => $imageIndex + 1],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-'.$imageId.'?auto=format&fit=crop&w=1600&q=85',
                        'alt_text' => $name.' oda görseli '.($imageIndex + 1),
                        'alt_text_en' => $nameEn.' room image '.($imageIndex + 1),
                        'is_primary' => $imageIndex === 0,
                    ],
                );
            }
        }
    }
}
