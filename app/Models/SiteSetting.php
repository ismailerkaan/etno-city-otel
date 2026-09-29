<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'hotel_name', 'phone', 'email', 'address', 'logo_url', 'instagram_url', 'whatsapp_url',
    'facebook_url', 'youtube_url', 'tripadvisor_url',
    'hero_eyebrow', 'hero_title', 'hero_subtitle', 'hero_image_url',
    'hero_eyebrow_en', 'hero_title_en', 'hero_subtitle_en',
    'about_eyebrow', 'about_title', 'about_primary_text', 'about_secondary_text', 'about_image_url',
    'about_eyebrow_en', 'about_title_en', 'about_primary_text_en', 'about_secondary_text_en',
    'suite_count', 'award_badge_title', 'award_badge_subtitle',
    'award_badge_title_en', 'award_badge_subtitle_en',
    'amenities_eyebrow', 'amenities_title', 'amenities_subtitle',
    'amenities_eyebrow_en', 'amenities_title_en', 'amenities_subtitle_en',
    'events_eyebrow', 'events_title', 'events_subtitle',
    'events_eyebrow_en', 'events_title_en', 'events_subtitle_en',
    'gallery_eyebrow', 'gallery_title', 'gallery_description',
    'gallery_eyebrow_en', 'gallery_title_en', 'gallery_description_en',
    'testimonials_eyebrow', 'testimonials_title', 'testimonials_description',
    'testimonials_eyebrow_en', 'testimonials_title_en', 'testimonials_description_en',
    'location_eyebrow', 'location_eyebrow_en', 'location_title', 'location_title_en',
    'location_description', 'location_description_en',
    'location_map_embed_url', 'location_coordinates', 'location_address', 'location_pin_label',
    'newsletter_title', 'newsletter_text',
    'newsletter_title_en', 'newsletter_text_en',
])]
class SiteSetting extends Model
{
    public function translated(string $attribute): mixed
    {
        if (app()->getLocale() === 'en') {
            return $this->getAttribute($attribute.'_en') ?: $this->getAttribute($attribute);
        }

        return $this->getAttribute($attribute);
    }

    public function logoUrl(): ?string
    {
        if (empty($this->logo_url)) {
            return null;
        }

        if (str_starts_with($this->logo_url, 'http://') || str_starts_with($this->logo_url, 'https://')) {
            return $this->logo_url;
        }

        return asset('storage/'.$this->logo_url);
    }

    public function aboutImageUrl(): string
    {
        if (empty($this->about_image_url)) {
            return '';
        }

        if (str_starts_with($this->about_image_url, 'http://') || str_starts_with($this->about_image_url, 'https://')) {
            return $this->about_image_url;
        }

        return asset('storage/'.$this->about_image_url);
    }

    public function mapEmbedUrl(): string
    {
        $raw = $this->location_map_embed_url;
        if (empty($raw)) {
            return 'https://maps.google.com/maps?q=37.122889,27.278917&hl=tr&z=14&output=embed';
        }

        if (preg_match('/src="([^"]+)"/i', $raw, $matches)) {
            return $matches[1];
        }

        return $raw;
    }

    protected function casts(): array
    {
        return [
            'suite_count' => 'integer',
        ];
    }
}
