<?php

namespace Tests\Feature;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_gallery(): void
    {
        $this->get(route('admin.gallery.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_gallery_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.gallery.index'))
            ->assertOk()
            ->assertSee('Galeri Yönetimi');
    }

    public function test_admin_can_create_gallery_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.gallery.categories.store'), [
                'name' => 'Havuz & Spa',
                'name_en' => 'Pool & Spa',
                'sort_order' => 5,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_categories', [
            'name' => 'Havuz & Spa',
            'name_en' => 'Pool & Spa',
            'slug' => 'havuz-spa',
        ]);
    }

    public function test_admin_can_update_gallery_section_headers_and_description(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->put(route('admin.gallery.section-update'), [
                'gallery_eyebrow' => 'Özel Arşiv',
                'gallery_title' => 'Büyülü Mekanlar',
                'gallery_description' => 'Yeni güncellenmiş galeri açıklaması.',
                'gallery_eyebrow_en' => 'Exclusive Archive',
                'gallery_title_en' => 'Magical Spaces',
                'gallery_description_en' => 'New updated gallery description.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('site_settings', [
            'gallery_title' => 'Büyülü Mekanlar',
            'gallery_description' => 'Yeni güncellenmiş galeri açıklaması.',
            'gallery_description_en' => 'New updated gallery description.',
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Yeni güncellenmiş galeri açıklaması.');
    }

    public function test_admin_can_upload_images_to_category(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = GalleryCategory::query()->create([
            'name' => 'Odalar',
            'name_en' => 'Rooms',
            'slug' => 'odalar',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.gallery.images.store', $category), [
                'images' => [
                    UploadedFile::fake()->image('room1.jpg'),
                    UploadedFile::fake()->image('room2.jpg'),
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertCount(2, $category->fresh()->images);
    }

    public function test_admin_can_batch_update_images(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = GalleryCategory::query()->create([
            'name' => 'Odalar',
            'name_en' => 'Rooms',
            'slug' => 'odalar',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $image = GalleryImage::query()->create([
            'gallery_category_id' => $category->id,
            'image_path' => 'gallery/test.jpg',
            'tag' => 'Eski Etiket',
            'tag_en' => 'Old Tag',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.gallery.images.batch-update'), [
                'category_id' => $category->id,
                'images' => [
                    $image->id => [
                        'tag' => 'Yeni Lüks Teras',
                        'tag_en' => 'New Luxury Terrace',
                        'sort_order' => 3,
                        'is_active' => '1',
                    ],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_images', [
            'id' => $image->id,
            'tag' => 'Yeni Lüks Teras',
            'tag_en' => 'New Luxury Terrace',
            'sort_order' => 3,
            'is_active' => true,
        ]);
    }

    public function test_homepage_renders_gallery_section_with_images_and_link_to_gallery(): void
    {
        $category = GalleryCategory::query()->create([
            'name' => 'Süitler',
            'name_en' => 'Suites',
            'slug' => 'suitler',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        GalleryImage::query()->create([
            'gallery_category_id' => $category->id,
            'image_path' => 'https://example.com/suite.jpg',
            'tag' => 'Özel Deniz Manzarası',
            'tag_en' => 'Exclusive Ocean View',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Tüm Galeriyi İncele')
            ->assertSee(route('gallery'))
            ->assertSee('Özel Deniz Manzarası');

        $this->withSession(['locale' => 'en'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Explore Full Gallery')
            ->assertSee('Exclusive Ocean View');
    }

    public function test_public_gallery_page_renders_all_categories_and_images(): void
    {
        $category = GalleryCategory::query()->create([
            'name' => 'Özel Havuzlar',
            'name_en' => 'Private Pools',
            'slug' => 'ozel-havuzlar',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        GalleryImage::query()->create([
            'gallery_category_id' => $category->id,
            'image_path' => 'https://example.com/pool.jpg',
            'tag' => 'Sonsuzluk Havuzu Keyfi',
            'tag_en' => 'Infinity Pool Bliss',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Özel Havuzlar')
            ->assertSee('Sonsuzluk Havuzu Keyfi');

        $this->withSession(['locale' => 'en'])
            ->get(route('gallery'))
            ->assertOk()
            ->assertSee('Private Pools')
            ->assertSee('Infinity Pool Bliss');
    }

    public function test_gallery_english_alias_redirects_to_galeri(): void
    {
        $this->get('/gallery')->assertRedirect(route('gallery'));
    }
}
