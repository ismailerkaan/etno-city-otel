<?php

namespace Database\Factories;

use App\Models\RoomImage;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomImage>
 */
class RoomImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'image_url' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1600&q=85',
            'alt_text' => fake()->sentence(3), 'alt_text_en' => fake()->sentence(3),
            'sort_order' => 1, 'is_primary' => true,
        ];
    }
}
