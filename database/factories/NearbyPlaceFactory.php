<?php

namespace Database\Factories;

use App\Models\NearbyPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NearbyPlace>
 */
class NearbyPlaceFactory extends Factory
{
    protected $model = NearbyPlace::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon' => fake()->randomElement(['flight', 'sailing', 'anchor', 'directions_car', 'beach_access']),
            'title' => fake()->city().' Noktası',
            'title_en' => fake()->city().' Spot',
            'description' => 'Özel Araç: '.fake()->numberBetween(10, 45).' dk',
            'description_en' => 'Private Car: '.fake()->numberBetween(10, 45).' min',
            'distance' => fake()->numberBetween(5, 50).' km',
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
