<?php

namespace App\Models;

use Database\Factories\RoomImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'room_type_id',
    'image_url',
    'alt_text',
    'alt_text_en',
    'sort_order',
    'is_primary',
])]
class RoomImage extends Model
{
    /** @use HasFactory<RoomImageFactory> */
    use HasFactory;

    /** @return BelongsTo<RoomType, $this> */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function imageUrl(): string
    {
        if (empty($this->image_url)) {
            return '';
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        return asset('storage/'.$this->image_url);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_primary' => 'boolean',
        ];
    }
}
