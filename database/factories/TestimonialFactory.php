<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_name' => fake()->name(),
            'author_title' => fake()->city().', '.fake()->country().' · '.fake()->word().' Süiti',
            'author_title_en' => fake()->city().', '.fake()->country().' · '.fake()->word().' Suite',
            'comment' => fake()->paragraph(),
            'comment_en' => fake()->paragraph(),
            'rating' => 5,
            'avatar_url' => null,
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
