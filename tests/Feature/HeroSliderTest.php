<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSliderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_multiple_slider_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.hero-slides.store'), [
                'images' => [
                    UploadedFile::fake()->createWithContent('first.jpg', str_repeat('x', 100), 'image/jpeg'),
                    UploadedFile::fake()->createWithContent('second.png', str_repeat('x', 100), 'image/png'),
                ],
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseCount('hero_slides', 2);
        HeroSlide::query()->each(fn (HeroSlide $slide) => Storage::disk('public')->assertExists($slide->image_path));
    }

    public function test_homepage_renders_only_active_slides(): void
    {
        HeroSlide::query()->create([
            'image_path' => 'hero-slides/active.jpg',
            'title' => 'Aktif görsel',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        HeroSlide::query()->create([
            'image_path' => 'hero-slides/passive.jpg',
            'title' => 'Pasif görsel',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Aktif görsel')
            ->assertDontSee('Pasif görsel');
    }
}
