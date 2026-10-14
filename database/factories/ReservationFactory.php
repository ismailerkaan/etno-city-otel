<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => fake()->unique()->regexify('RSV[0-9A-Z]{12}'),
            'room_type_id' => RoomType::factory(), 'status' => Reservation::STATUS_PAYMENT_PENDING,
            'check_in' => now()->addWeek()->toDateString(), 'check_out' => now()->addWeek()->addDays(2)->toDateString(),
            'nights' => 2, 'adults' => 2, 'children' => 0,
            'first_name' => fake()->firstName(), 'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(), 'phone' => fake()->phoneNumber(),
            'special_requests' => null, 'room_name' => 'Standart', 'nightly_price' => 3200,
            'total_price' => 6400, 'currency' => 'TRY',
        ];
    }
}
