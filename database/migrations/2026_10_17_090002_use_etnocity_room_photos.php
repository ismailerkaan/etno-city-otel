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
        $this->updateRoomImages($this->etnocityImages());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->updateRoomImages($this->originalImages());
    }

    /** @param array<string, array<int, string>> $roomImages */
    private function updateRoomImages(array $roomImages): void
    {
        foreach ($roomImages as $slug => $images) {
            $roomTypeId = DB::table('room_types')->where('slug', $slug)->value('id');

            if ($roomTypeId === null) {
                continue;
            }

            foreach ($images as $index => $imageUrl) {
                DB::table('room_images')->updateOrInsert(
                    ['room_type_id' => $roomTypeId, 'sort_order' => $index + 1],
                    [
                        'image_url' => $imageUrl,
                        'alt_text' => $this->altText($slug, $index),
                        'alt_text_en' => $this->altText($slug, $index, true),
                        'is_primary' => $index === 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }
    }

    /** @return array<string, array<int, string>> */
    private function etnocityImages(): array
    {
        return [
            'ekonomik' => ['/images/rooms/economy-room.jpg', '/images/rooms/economy-amenities.jpg', '/images/rooms/economy-bathroom.jpg'],
            'single' => ['/images/rooms/single-room.jpg', '/images/rooms/single-wide-view.jpg', '/images/rooms/single-bathroom.jpg'],
            'standart' => ['/images/rooms/standard-room.jpg', '/images/rooms/standard-amenities.jpg', '/images/rooms/standard-bathroom.jpg'],
            'balkonlu-oda' => ['/images/rooms/balcony-room.jpg', '/images/rooms/balcony-room-reverse.jpg', '/images/rooms/balcony-bathroom.jpg'],
            'manzarali-oda' => ['/images/rooms/view-room.jpg', '/images/rooms/view-panorama.jpg', '/images/rooms/view-landscape.jpg'],
            'aile-odasi' => ['/images/rooms/family-room.jpg', '/images/rooms/family-hotel-atrium.jpg', '/images/rooms/family-bathroom.jpg'],
        ];
    }

    /** @return array<string, array<int, string>> */
    private function originalImages(): array
    {
        return [
            'ekonomik' => $this->unsplashImages(['1566665797739-1674de7a421a', '1560185007-c5ca9d2c014d', '1542314831-068cd1dbfeeb']),
            'single' => $this->unsplashImages(['1590490360182-c33d57733427', '1618773928121-c32242e63f39', '1551882547-ff40c63fe5fa']),
            'standart' => $this->unsplashImages(['1611892440504-42a792e24d32', '1578683010236-d716f9a3f461', '1596394516093-501ba68a0ba6']),
            'balkonlu-oda' => $this->unsplashImages(['1582719478250-c89cae4dc85b', '1566073771259-6a8506099945', '1584132967334-10e028bd69f7']),
            'manzarali-oda' => $this->unsplashImages(['1591088398332-8a7791972843', '1598928506311-c55ded91a20c', '1615874694520-474822394e73']),
            'aile-odasi' => $this->unsplashImages(['1564501049412-61c2a3083791', '1578683010236-d716f9a3f461', '1596394516093-501ba68a0ba6']),
        ];
    }

    /**
     * @param  array<int, string>  $imageIds
     * @return array<int, string>
     */
    private function unsplashImages(array $imageIds): array
    {
        return array_map(
            fn (string $imageId): string => 'https://images.unsplash.com/photo-'.$imageId.'?auto=format&fit=crop&w=1600&q=85',
            $imageIds,
        );
    }

    private function altText(string $slug, int $index, bool $english = false): string
    {
        $roomNames = [
            'ekonomik' => ['Ekonomik', 'Economy'],
            'single' => ['Single', 'Single'],
            'standart' => ['Standart', 'Standard'],
            'balkonlu-oda' => ['Balkonlu Oda', 'Balcony Room'],
            'manzarali-oda' => ['Manzaralı Oda', 'View Room'],
            'aile-odasi' => ['Aile Odası', 'Family Room'],
        ];

        return $roomNames[$slug][$english ? 1 : 0].($english ? ' room image ' : ' oda görseli ').($index + 1);
    }
};
