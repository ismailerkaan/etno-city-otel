<?php

namespace App\Services\Hms;

use App\Models\Reservation;

/**
 * Sends reservation changes to HMS and records the outcome on the reservation.
 *
 * Every method throws HmsException when HMS does not accept the change; the error is stored in hms_sync_error.
 */
class HmsReservationSync
{
    public function __construct(private readonly HmsClient $hms) {}

    /** Push a new booking (or re-send a failed one; HMS updates instead of duplicating for the same ID). */
    public function book(Reservation $reservation): void
    {
        $hmsBookingId = $this->push($reservation, 'Book');

        $reservation->update([
            'status' => Reservation::STATUS_CONFIRMED,
            'payment_method' => $reservation->payment_method ?? 'pay_at_hotel',
            'hms_booking_id' => $hmsBookingId ?: $reservation->hms_booking_id,
            'hms_synced_at' => now(),
            'hms_sync_error' => null,
        ]);
    }

    /** Send the reservation's current (possibly unsaved) content as a modification and save it once HMS accepts. */
    public function modify(Reservation $reservation): void
    {
        $this->push($reservation, 'Modify');

        $reservation->update(['hms_synced_at' => now(), 'hms_sync_error' => null]);
    }

    public function cancel(Reservation $reservation): void
    {
        $this->push($reservation, 'Cancel');

        $reservation->update([
            'status' => Reservation::STATUS_CANCELLED,
            'hms_synced_at' => now(),
            'hms_sync_error' => null,
        ]);
    }

    private function push(Reservation $reservation, string $type): int
    {
        try {
            return $this->hms->pushBooking(HmsBookingPayload::fromReservation($reservation, $this->hms->hotelId(), $type));
        } catch (HmsException $exception) {
            report($exception);

            // A rejected Modify/Cancel is not applied locally either, so both sides still agree: only a booking
            // that never reached HMS needs to be flagged for re-sending
            if ($type !== 'Book') {
                throw $exception;
            }

            // Store only the error: unsaved changes on the model must not be persisted when HMS rejected them
            $error = '['.$type.'] '.$exception->getMessage();
            $reservation->newQuery()->whereKey($reservation->getKey())->update(['hms_sync_error' => $error]);
            $reservation->hms_sync_error = $error;
            $reservation->syncOriginalAttribute('hms_sync_error');

            throw $exception;
        }
    }
}
