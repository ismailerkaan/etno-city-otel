<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 99999),
            'name' => Str::title($name), 'name_en' => Str::title($name),
            'description' => fake()->sentence(), 'description_en' => fake()->sentence(),
            'nightly_price' => fake()->numberBetween(2000, 8000), 'currency' => 'TRY',
            'max_adults' => 2, 'max_children' => 1, 'size_sqm' => 28,
            'bed_type' => '1 Çift Kişilik Yatak', 'bed_type_en' => '1 Double Bed',
            'features' => ['Klima', 'Wi-Fi'], 'features_en' => ['Air Conditioning', 'Wi-Fi'],
            'sort_order' => 1, 'is_active' => true, 'show_on_home' => false,
        ];
    }
}
