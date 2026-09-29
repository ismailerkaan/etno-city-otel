<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'image_path', 'category', 'category_en',
    'title', 'title_en', 'description', 'description_en',
    'button_text', 'button_text_en', 'button_url',
    'show_on_home', 'is_active', 'sort_order',
])]
class Event extends Model
{
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope]
    protected function forHome(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('show_on_home', true);
    }

    public function imageUrl(): string
    {
        if (empty($this->image_path)) {
            return '';
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/'.$this->image_path);
    }

    public function translatedTitle(): string
    {
        if (app()->isLocale('en') && ! empty($this->title_en)) {
            return $this->title_en;
        }

        return $this->title ?? '';
    }

    public function translatedCategory(): string
    {
        if (app()->isLocale('en') && ! empty($this->category_en)) {
            return $this->category_en;
        }

        return $this->category ?? '';
    }

    public function translatedDescription(): string
    {
        if (app()->isLocale('en') && ! empty($this->description_en)) {
            return $this->description_en;
        }

        return $this->description ?? '';
    }

    public function translatedButtonText(): string
    {
        if (app()->isLocale('en') && ! empty($this->button_text_en)) {
            return $this->button_text_en;
        }

        return $this->button_text ?: 'Deneyimi Rezerve Et';
    }

    protected function casts(): array
    {
        return [
            'show_on_home' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
