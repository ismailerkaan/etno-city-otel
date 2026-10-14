<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

#[Fillable(['icon', 'title', 'title_en', 'description', 'description_en', 'sort_order', 'is_active'])]
class ReservationGuarantee extends Model
{
    /**
     * Ensure table exists or attempt migration if missing.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (! Schema::hasTable('reservation_guarantees')) {
                Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Throwable) {
            // Silently continue if DB connection or permissions prevent runtime migration
        }
    }

    /**
     * Default raw guarantees data.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultGuaranteesData(): array
    {
        return [
            [
                'icon' => 'format_image_left',
                'title' => 'Doğrudan Fiyat Güvencesi',
                'title_en' => 'Direct Rate Assurance',
                'description' => 'Varışta doğrudan yarımada ayrıcalığı ile dünya genelinde garantili en düşük ücret.',
                'description_en' => 'Guaranteed lowest rate worldwide with direct peninsula privilege upon arrival.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => 'price_check',
                'title' => 'Şeffaf Hesap',
                'title_en' => 'Transparent Folio',
                'description' => 'Gizli tesis ücretleri, varış yeri ek ücretleri veya ayrılışta açıklanmamış enerji ekleri bulunmamaktadır.',
                'description_en' => 'No hidden resort fees, destination surcharges, or undisclosed energy supplements upon departure.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'icon' => 'support_agent',
                'title' => '7/24 Yarımada Ev Sahibi',
                'title_en' => '24/7 Peninsula Host',
                'description' => 'Konaklamanızdan önce, süresince ve sonrasında Bodrum concierge masasıyla kesintisiz doğrudan iletişim.',
                'description_en' => 'Uninterrupted direct liaison with our Bodrum concierge desk before, during, and after your stay.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'icon' => 'event_repeat',
                'title' => 'Esnek Depozito Politikası',
                'title_en' => 'Flexible Deposit Policy',
                'description' => 'Mütevazı bir ön ödemeli rezervasyon; bakiye tesise varışta ödenir.',
                'description_en' => 'Secure your reservation with a modest prepaid retainer; balance settled upon arrival at the estate.',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];
    }

    /**
     * Fallback default guarantees when table does not exist.
     *
     * @return Collection<int, ReservationGuarantee>
     */
    public static function defaultGuarantees(): Collection
    {
        return collect(self::defaultGuaranteesData())->map(function (array $item, int $index) {
            $model = new self($item);
            $model->id = $index + 1;

            return $model;
        });
    }

    /**
     * Seed default items into the database table if missing.
     */
    public static function seedDefaults(): void
    {
        self::ensureTableExists();

        if (Schema::hasTable('reservation_guarantees')) {
            foreach (self::defaultGuaranteesData() as $item) {
                self::firstOrCreate(
                    ['title' => $item['title']],
                    $item
                );
            }
        }
    }

    /**
     * Retrieve active guarantees safely without throwing table missing errors.
     *
     * @return Collection<int, ReservationGuarantee>
     */
    public static function getActiveGuarantees(): Collection
    {
        try {
            self::ensureTableExists();

            if (Schema::hasTable('reservation_guarantees')) {
                if (self::query()->count() === 0) {
                    self::seedDefaults();
                }

                $items = self::query()->active()->orderBy('sort_order')->orderBy('id')->get();
                if ($items->isNotEmpty()) {
                    return $items;
                }
            }
        } catch (\Throwable) {
            // Silently fallback
        }

        return self::defaultGuarantees();
    }

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
     * Available Material Symbols icons for reservation guarantees.
     *
     * @return array<string, string>
     */
    public static function availableIcons(): array
    {
        return [
            'format_image_left' => 'Resim / Belge Güvencesi',
            'price_check' => 'Fiyat Kontrol / Şeffaf Hesap',
            'support_agent' => '7/24 Destek / Concierge',
            'event_repeat' => 'Esnek Rezervasyon / Takvim',
            'verified' => 'Doğrulanmış / Resmi Garanti',
            'shield' => 'Güvenlik / Rezervasyon Koruması',
            'lock' => 'Güvenli Ödeme',
            'credit_card' => 'Kredi Kartı / Esnek Depozito',
            'free_cancellation' => 'Ücretsiz İptal',
            'handshake' => 'Doğrudan Fiyat Anlaşması',
            'hotel' => 'Oda & Konaklama Garantisi',
            'concierge' => 'Kişisel Butler / Ev Sahibi',
            'room_service' => '7/24 Hizmet',
            'spa' => 'Sağlık & İkram',
            'flight_takeoff' => 'VIP Transfer / Helipad',
            'directions_car' => 'Vale Hizmeti',
            'check_circle' => 'Onay İşareti',
            'star' => 'Yıldız / Ayrıcalık',
            'diamond' => 'Lüks / Prestij',
            'key' => 'Hızlı Giriş / Anahtar',
            'wifi' => 'Starlink / Kesintisiz Bağlantı',
            'eco' => 'Doğal & Botanik',
            'calendar_month' => 'Tarih Değişikliği',
            'thumb_up' => 'Memnuniyet Garantisi',
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
