<?php

namespace App\Services\Hms;

use App\Models\Reservation;

/**
 * Builds the BookingPushRQ booking object for a reservation.
 */
class HmsBookingPayload
{
    /** @return array<string, mixed> */
    public static function fromReservation(Reservation $reservation, int $hotelId, string $type = 'Book'): array
    {
        $checkIn = $reservation->check_in->toDateString();
        $checkOut = $reservation->check_out->toDateString();
        $total = number_format((float) $reservation->total_price, 2, '.', '');
        $currency = $reservation->currency ?: 'TRY';

        return [
            'hotelID' => (string) $hotelId,
            // Same ID on retry updates the existing HMS record instead of duplicating it
            'ID' => $reservation->reference,
            'type' => $type,
            'createDateTime' => $reservation->created_at->toIso8601String(),
            'checkinDate' => $checkIn,
            'checkoutDate' => $checkOut,
            'RoomStays' => [[
                'roomTypeID' => (string) $reservation->hms_room_type_id,
                'roomName' => $reservation->room_name,
                'ratePlanID' => (string) $reservation->hms_rate_plan_id,
                'ratePlanName' => $reservation->board_name,
                'type' => $type,
                'NumberOfUnits' => '1',
                'checkinDate' => $checkIn,
                'checkoutDate' => $checkOut,
                'GuestCount' => [
                    'adult' => $reservation->adults,
                    'child' => $reservation->children,
                ],
                'PerDayRates' => [
                    'currency' => $currency,
                    'PerDayRate' => collect($reservation->daily_rates ?? [])->map(fn (array $day): array => [
                        'stayDate' => $day['date'],
                        'baseRate' => $day['price'],
                        'hotelServiceFees' => '0',
                    ])->values()->all(),
                ],
                'Total' => [
                    'amountAfterTaxes' => $total,
                    'amountOfTaxes' => '0.00',
                    'currency' => $currency,
                ],
            ]],
            'PrimaryGuests' => [[
                'name' => $reservation->first_name,
                'surname' => $reservation->last_name,
                'PhoneNumber' => $reservation->phone,
                'email' => $reservation->email,
                'CountryCode' => 'TR',
            ]],
            'ChildGuests' => collect($reservation->child_ages ?? [])->map(fn (int $age): array => ['age' => $age])->values()->all(),
            'SpecialRequest' => collect([
                $reservation->special_requests,
                'Ödeme: Otelde alınacaktır (web sitesi rezervasyonu, online ödeme alınmadı).',
                $reservation->non_refundable ? 'Fiyat tipi: İade edilemez [NR].' : null,
            ])->filter()->map(fn (string $text): array => ['text' => $text])->values()->all(),
            'extras' => [],
            'Total' => [
                'amountAfterTaxes' => $total,
                'amountOfTaxes' => '0.00',
                'extraTotal' => '0.00',
                'currency' => $currency,
            ],
        ];
    }
}
