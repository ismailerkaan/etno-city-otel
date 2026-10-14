<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\RoomImage;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomTypeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_room_management(): void
    {
        $this->get(route('admin.rooms.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_room_types(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        RoomType::factory()->create(['name' => 'Balkonlu Oda']);

        $this->actingAs($admin)->get(route('admin.rooms.index'))
            ->assertOk()
            ->assertSee('Oda Tipleri')
            ->assertSee('Balkonlu Oda');
    }

    public function test_admin_can_create_room_type_with_an_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.rooms.store'), [
            'name' => 'Yeni Oda', 'name_en' => 'New Room', 'slug' => 'yeni-oda',
            'description' => 'Yeni oda açıklaması', 'nightly_price' => 4500,
            'max_adults' => 2, 'max_children' => 1, 'size_sqm' => 30,
            'bed_type' => '1 King Yatak', 'features_text' => "Klima\nWi-Fi",
            'sort_order' => 7, 'is_active' => '1', 'show_on_home' => '1',
            'images' => [[
                'file' => UploadedFile::fake()->image('yeni-oda.jpg', 1200, 800),
                'alt_text' => 'Yeni oda', 'is_primary' => '1',
            ]],
        ]);

        $roomType = RoomType::query()->where('slug', 'yeni-oda')->firstOrFail();
        $response->assertRedirect(route('admin.rooms.edit', $roomType))->assertSessionHas('success');
        $this->assertSame('4500.00', $roomType->nightly_price);
        $this->assertSame(['Klima', 'Wi-Fi'], $roomType->features);
        $this->assertTrue($roomType->show_on_home);
        $this->assertSame(1, $roomType->images()->count());
        Storage::disk('public')->assertExists($roomType->images()->firstOrFail()->image_url);
    }

    public function test_admin_can_update_price_content_and_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $roomType = RoomType::factory()->create(['name' => 'Eski Oda']);
        $image = RoomImage::factory()->for($roomType)->create();

        $this->actingAs($admin)->put(route('admin.rooms.update', $roomType), [
            'name' => 'Güncel Oda', 'name_en' => 'Updated Room', 'slug' => $roomType->slug,
            'description' => 'Güncel açıklama', 'nightly_price' => 5100,
            'max_adults' => 3, 'max_children' => 2, 'size_sqm' => 42,
            'bed_type' => '1 King Yatak', 'features_text' => "Balkon\nKahve Makinesi",
            'sort_order' => 2, 'is_active' => '1', 'show_on_home' => '1',
            'images' => [[
                'id' => $image->id, 'file' => UploadedFile::fake()->image('guncel-oda.jpg', 1200, 800),
                'alt_text' => 'Güncel oda görseli', 'is_primary' => '1',
            ]],
        ])->assertRedirect()->assertSessionHas('success');

        $roomType->refresh();
        $this->assertSame('Güncel Oda', $roomType->name);
        $this->assertSame('5100.00', $roomType->nightly_price);
        $this->assertSame(['Balkon', 'Kahve Makinesi'], $roomType->features);
        $this->assertTrue($roomType->show_on_home);
        $updatedImage = $roomType->images()->firstOrFail();
        $this->assertStringStartsWith('rooms/'.$roomType->id.'/', $updatedImage->image_url);
        Storage::disk('public')->assertExists($updatedImage->image_url);
    }

    public function test_room_type_with_reservations_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $roomType = RoomType::factory()->create();
        Reservation::factory()->for($roomType)->create();

        $this->actingAs($admin)->delete(route('admin.rooms.destroy', $roomType))
            ->assertRedirect()
            ->assertSessionHasErrors('room');

        $this->assertModelExists($roomType);
    }

    public function test_home_page_displays_only_active_room_types_selected_for_home(): void
    {
        $selectedRoom = RoomType::factory()->create([
            'name' => 'Anasayfa Seçili Oda',
            'show_on_home' => true,
            'is_active' => true,
        ]);
        RoomImage::factory()->for($selectedRoom)->create();
        RoomType::factory()->create(['name' => 'Seçilmemiş Oda', 'show_on_home' => false, 'is_active' => true]);
        RoomType::factory()->create(['name' => 'Pasif Seçili Oda', 'show_on_home' => true, 'is_active' => false]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Anasayfa Seçili Oda')
            ->assertDontSee('Seçilmemiş Oda')
            ->assertDontSee('Pasif Seçili Oda');
    }
}
