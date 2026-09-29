<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['image_path', 'title', 'title_en', 'description', 'description_en', 'sort_order', 'is_active'])]
class HeroSlide extends Model
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

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
