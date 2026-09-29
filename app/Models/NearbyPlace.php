<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'icon',
    'title',
    'title_en',
    'description',
    'description_en',
    'distance',
    'sort_order',
    'is_active',
])]
class NearbyPlace extends Model
{
    use HasFactory;

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
     * Available Material Symbols icons for nearby places and transport.
     *
     * @return array<string, string>
     */
    public static function availableIcons(): array
    {
        return [
            'flight' => 'Uçuş / Havalimanı (flight)',
            'sailing' => 'Yat / Marina / Deniz (sailing)',
            'anchor' => 'Liman / İskele (anchor)',
            'directions_boat' => 'Tekne / Bot Transferi (directions_boat)',
            'directions_car' => 'Özel Araç / Karayolu (directions_car)',
            'local_taxi' => 'Taksi / VIP Transfer (local_taxi)',
            'beach_access' => 'Plaj / Sahil / Gizli Koy (beach_access)',
            'castle' => 'Tarihi Kale / Antik Kent (castle)',
            'museum' => 'Müze / Kültür Noktası (museum)',
            'restaurant' => 'Restoran / Gastronomi (restaurant)',
            'local_bar' => 'Gece Kulübü / Bar (local_bar)',
            'shopping_bag' => 'Çarşı / Alışveriş (shopping_bag)',
            'hiking' => 'Yürüyüş / Doğa Parkuru (hiking)',
            'location_city' => 'Şehir Merkezi (location_city)',
            'golf_course' => 'Golf Sahası (golf_course)',
            'local_hospital' => 'Hastane / Sağlık Merkezi (local_hospital)',
            'location_on' => 'Konum / İşaretçi (location_on)',
            'explore' => 'Keşif / Rota (explore)',
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
