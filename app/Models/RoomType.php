<?php

namespace App\Models;

use Database\Factories\RoomTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug',
    'name',
    'name_en',
    'description',
    'description_en',
    'nightly_price',
    'currency',
    'max_adults',
    'max_children',
    'size_sqm',
    'bed_type',
    'bed_type_en',
    'features',
    'features_en',
    'sort_order',
    'is_active',
    'show_on_home',
])]
class RoomType extends Model
{
    /** @use HasFactory<RoomTypeFactory> */
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return HasMany<RoomImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /** @return HasMany<Reservation, $this> */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function translatedName(): string
    {
        return app()->isLocale('en') && $this->name_en ? $this->name_en : $this->name;
    }

    public function translatedDescription(): string
    {
        return app()->isLocale('en') && $this->description_en ? $this->description_en : ($this->description ?? '');
    }

    /** @return array<int, string> */
    public function translatedFeatures(): array
    {
        if (app()->isLocale('en') && $this->features_en) {
            return $this->features_en;
        }

        return $this->features ?? [];
    }

    public function translatedBedType(): string
    {
        return app()->isLocale('en') && $this->bed_type_en ? $this->bed_type_en : ($this->bed_type ?? '');
    }

    public function primaryImageUrl(): ?string
    {
        $primaryImage = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        return $primaryImage?->imageUrl();
    }

    protected function casts(): array
    {
        return [
            'nightly_price' => 'decimal:2',
            'max_adults' => 'integer',
            'max_children' => 'integer',
            'size_sqm' => 'integer',
            'features' => 'array',
            'features_en' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'show_on_home' => 'boolean',
        ];
    }
}
