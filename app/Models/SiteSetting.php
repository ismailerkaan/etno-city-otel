<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

#[Fillable([
    'hotel_name', 'phone', 'email', 'address', 'logo_url', 'instagram_url', 'whatsapp_url',
    'facebook_url', 'youtube_url', 'tripadvisor_url',
    'google_analytics_measurement_id', 'google_search_console_verification',
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
    'guarantees_eyebrow', 'guarantees_title',
    'guarantees_eyebrow_en', 'guarantees_title_en',
    'contact_eyebrow', 'contact_title', 'contact_subtitle',
    'contact_eyebrow_en', 'contact_title_en', 'contact_subtitle_en',
    'contact_concierge_hours', 'contact_concierge_hours_en',
    'contact_phone_concierge', 'contact_email_reservations',
])]
class SiteSetting extends Model
{
    public function translated(string $attribute): mixed
    {
        $value = app()->getLocale() === 'en'
            ? ($this->getAttribute($attribute.'_en') ?: $this->getAttribute($attribute))
            : $this->getAttribute($attribute);

        if ($attribute === 'location_title' && ($value === 'Sakin Ama Kolayca Ulaşılabilir' || $value === 'Yarımadanın Kalbinde, Sakin ve Ayrıcalıklı Bir Konum' || empty($value))) {
            return app()->getLocale() === 'en'
                ? 'Our Location'
                : 'Konumumuz';
        }

        if ($attribute === 'location_description' && (empty($value) || str_contains((string) $value, "Cape Artemis'in el değmemiş") || str_contains((string) $value, "Bodrum'un en dingin"))) {
            return app()->getLocale() === 'en'
                ? 'You can easily reach our hotel by getting directions.'
                : 'Yol tarifi alarak otelimize kolayca ulaşabilirsiniz.';
        }

        return $value;
    }

    public function directionsUrl(): string
    {
        if (! empty($this->location_map_embed_url)) {
            $embed = (string) $this->location_map_embed_url;
            if (preg_match('/!3d([0-9\.\-]+)!2d([0-9\.\-]+)/', $embed, $m)) {
                return 'https://www.google.com/maps/dir/?api=1&destination='.$m[1].','.$m[2];
            }
            if (preg_match('/!2d([0-9\.\-]+)!3d([0-9\.\-]+)/', $embed, $m)) {
                return 'https://www.google.com/maps/dir/?api=1&destination='.$m[2].','.$m[1];
            }
            if (preg_match('/[?&](?:q|ll)=([0-9\.\-]+),([0-9\.\-]+)/', $embed, $m)) {
                return 'https://www.google.com/maps/dir/?api=1&destination='.$m[1].','.$m[2];
            }
            if (preg_match('/[?&]query=([^&"]+)/', $embed, $m)) {
                return 'https://www.google.com/maps/dir/?api=1&destination='.$m[1];
            }
        }

        if (! empty($this->location_coordinates)) {
            $coordinates = trim((string) $this->location_coordinates);
            if (preg_match('/([0-9\.\-]+)\s*,\s*([0-9\.\-]+)/', $coordinates, $matches)) {
                return 'https://www.google.com/maps/dir/?api=1&destination='.$matches[1].','.$matches[2];
            }

            return 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($coordinates);
        }

        $destination = trim(($this->hotel_name ?? 'EtnoCity').' '.($this->address ?: 'Bodrum Muğla'));

        return 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($destination);
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

    /**
     * Ensure contact columns exist in site_settings table.
     */
    public static function ensureContactColumnsExist(): void
    {
        try {
            if (! Schema::hasColumn('site_settings', 'contact_eyebrow')) {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                } catch (\Throwable) {
                }

                if (! Schema::hasColumn('site_settings', 'contact_eyebrow')) {
                    Schema::table('site_settings', function (Blueprint $table) {
                        if (! Schema::hasColumn('site_settings', 'contact_eyebrow')) {
                            $table->text('contact_eyebrow')->nullable();
                            $table->text('contact_title')->nullable();
                            $table->text('contact_subtitle')->nullable();
                            $table->text('contact_eyebrow_en')->nullable();
                            $table->text('contact_title_en')->nullable();
                            $table->text('contact_subtitle_en')->nullable();
                            $table->text('contact_concierge_hours')->nullable();
                            $table->text('contact_concierge_hours_en')->nullable();
                            $table->text('contact_phone_concierge')->nullable();
                            $table->text('contact_email_reservations')->nullable();
                        }
                    });
                }
            }
        } catch (\Throwable) {
            // Silently continue
        }
    }

    protected function casts(): array
    {
        return [
            'suite_count' => 'integer',
        ];
    }
}
