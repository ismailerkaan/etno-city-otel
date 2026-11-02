<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'menu_category_id',
    'name',
    'name_en',
    'description',
    'description_en',
    'price',
    'sort_order',
    'is_active',
])]
class MenuItem extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function translatedName(): string
    {
        if (app()->isLocale('en') && ! empty($this->name_en)) {
            return $this->name_en;
        }

        return $this->name ?? '';
    }

    public function translatedDescription(): string
    {
        if (app()->isLocale('en') && ! empty($this->description_en)) {
            return $this->description_en;
        }

        return $this->description ?? '';
    }

    public function formattedPrice(): string
    {
        $decimals = fmod((float) $this->price, 1.0) === 0.0 ? 0 : 2;

        return number_format((float) $this->price, $decimals, ',', '.').' TL';
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
