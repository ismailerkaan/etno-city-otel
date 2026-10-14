<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'reference',
    'room_type_id',
    'status',
    'check_in',
    'check_out',
    'nights',
    'adults',
    'children',
    'first_name',
    'last_name',
    'email',
    'phone',
    'special_requests',
    'room_name',
    'nightly_price',
    'total_price',
    'currency',
    'payment_method',
    'payment_card_brand',
    'payment_card_last_four',
    'payment_transaction_reference',
    'paid_at',
])]
class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    public const STATUS_PAYMENT_PENDING = 'payment_pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    public static function referenceForId(int $id): string
    {
        return 'Rez-'.(999 + $id);
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    /** @return BelongsTo<RoomType, $this> */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function guestFullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'nights' => 'integer',
            'adults' => 'integer',
            'children' => 'integer',
            'nightly_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }
}
