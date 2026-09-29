<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['icon', 'title', 'title_en', 'description', 'description_en', 'sort_order', 'is_active'])]
class Amenity extends Model
{
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function translatedTitle(): string
    {
        if (app()->isLocale('en') && ! empty($this->title_en)) {
            return $this->title_en;
        }

        return $this->title ?? '';
    }

    public function translatedDescription(): string
    {
        if (app()->isLocale('en') && ! empty($this->description_en)) {
            return $this->description_en;
        }

        return $this->description ?? '';
    }

    /**
     * Available Material Symbols icons for amenities.
     *
     * @return array<string, string>
     */
    public static function availableIcons(): array
    {
        return [
            'pool' => 'Sonsuzluk / Havuz',
            'spa' => 'Spa & Hamam',
            'restaurant_menu' => 'Gurme Restoran',
            'beach_access' => 'Özel Plaj & Sahil',
            'flight_takeoff' => 'Helipad & Transfer',
            'wifi_tethering' => 'Yüksek Hızlı Wi-Fi',
            'room_service' => '7/24 Oda Servisi',
            'electric_car' => 'Vale & Elektrikli Araç',
            'fitness_center' => 'Fitness & Spor Salonu',
            'local_bar' => 'Bar & Kokteyl Salonu',
            'sailing' => 'Yat & Katamaran',
            'self_improvement' => 'Yoga & Ses Terapisi',
            'hot_tub' => 'Sıcak Jakuzi',
            'king_bed' => 'Özel King Süit',
            'concierge' => 'Kişisel Butler / Concierge',
            'coffee' => 'Artisan Kahve Barı',
            'forest' => 'Zeytin Bahçesi & Doğa',
            'deck' => 'Panoramik Gün Batımı Terası',
        ];
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
