<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'author_name',
    'author_title',
    'author_title_en',
    'comment',
    'comment_en',
    'rating',
    'avatar_url',
    'sort_order',
    'is_active',
])]
class Testimonial extends Model
{
    use HasFactory;

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function translatedTitle(): string
    {
        if (app()->isLocale('en') && ! empty($this->author_title_en)) {
            return $this->author_title_en;
        }

        return $this->author_title ?? '';
    }

    public function translatedComment(): string
    {
        if (app()->isLocale('en') && ! empty($this->comment_en)) {
            return $this->comment_en;
        }

        return $this->comment ?? '';
    }

    public function avatarUrl(): ?string
    {
        if (empty($this->avatar_url)) {
            return null;
        }

        if (str_starts_with($this->avatar_url, 'http://') || str_starts_with($this->avatar_url, 'https://')) {
            return $this->avatar_url;
        }

        return asset('storage/'.$this->avatar_url);
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
