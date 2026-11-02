<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_menu_page_lists_seeded_cafe_menu(): void
    {
        $this->get(route('menu'))
            ->assertOk()
            ->assertSee('Menümüz')
            ->assertSee('Sıcak İçecekler')
            ->assertSee('Türk Kahvesi (Lokum ile)')
            ->assertSee('90 TL')
            ->assertSee('Tatlılar & Kurabiyeler');

        $this->get('/menu')->assertRedirect(route('menu'));
    }

    public function test_public_menu_hides_inactive_items_and_categories(): void
    {
        $category = MenuCategory::create(['name' => 'Kahvaltı', 'sort_order' => 10, 'is_active' => true]);
        MenuItem::create(['menu_category_id' => $category->id, 'name' => 'Gizli Ürün', 'price' => 10, 'is_active' => false]);
        MenuItem::create(['menu_category_id' => $category->id, 'name' => 'Serpme Kahvaltı', 'price' => 450, 'is_active' => true]);

        $hidden = MenuCategory::create(['name' => 'Pasif Kategori', 'sort_order' => 11, 'is_active' => false]);
        MenuItem::create(['menu_category_id' => $hidden->id, 'name' => 'Pasif Kategori Ürünü', 'price' => 20]);

        $this->get(route('menu'))
            ->assertOk()
            ->assertSee('Serpme Kahvaltı')
            ->assertSee('450 TL')
            ->assertDontSee('Gizli Ürün')
            ->assertDontSee('Pasif Kategori');
    }

    public function test_public_menu_is_shown_in_english(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get(route('menu'))
            ->assertOk()
            ->assertSee('Our Menu')
            ->assertSee('Hot Drinks')
            ->assertSee('Turkish Coffee (with Turkish Delight)');
    }

    public function test_guests_cannot_access_admin_menu(): void
    {
        $this->get(route('admin.menu.index'))->assertRedirect(route('login'));
        $this->get(route('admin.menu.qr-code'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_menu_with_qr_code(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.menu.index'))
            ->assertOk()
            ->assertSee('Menü QR Kodu')
            ->assertSee('<svg', false)
            ->assertSee(route('menu'))
            ->assertSee('Sıcak İçecekler');
    }

    public function test_admin_can_download_qr_code_svg(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.menu.qr-code'))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertHeader('Content-Disposition', 'attachment; filename="menu-qr-kod.svg"');
    }

    public function test_admin_can_manage_categories(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.menu.categories.store'), [
                'name' => 'Kahvaltı',
                'name_en' => 'Breakfast',
                'sort_order' => 4,
                'is_active' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $category = MenuCategory::query()->where('name', 'Kahvaltı')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.menu.categories.update', $category), [
                'name' => 'Kahvaltılar',
                'sort_order' => 5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('menu_categories', ['id' => $category->id, 'name' => 'Kahvaltılar', 'is_active' => false]);

        MenuItem::create(['menu_category_id' => $category->id, 'name' => 'Omlet', 'price' => 150]);

        $this->actingAs($admin)
            ->delete(route('admin.menu.categories.destroy', $category))
            ->assertRedirect();

        $this->assertDatabaseMissing('menu_categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('menu_items', ['name' => 'Omlet']);
    }

    public function test_admin_can_manage_items(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = MenuCategory::query()->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.menu.items.store'), [
                'menu_category_id' => $category->id,
                'name' => 'Salep',
                'name_en' => 'Salep',
                'price' => '95.50',
                'sort_order' => 20,
                'is_active' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $item = MenuItem::query()->where('name', 'Salep')->firstOrFail();
        $this->assertSame('95,50 TL', $item->formattedPrice());

        $this->actingAs($admin)
            ->get(route('admin.menu.items.edit', $item))
            ->assertOk()
            ->assertSee('Salep');

        $this->actingAs($admin)
            ->put(route('admin.menu.items.update', $item), [
                'menu_category_id' => $category->id,
                'name' => 'Tarçınlı Salep',
                'price' => 100,
                'sort_order' => 20,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.menu.index'));

        $this->assertDatabaseHas('menu_items', ['id' => $item->id, 'name' => 'Tarçınlı Salep', 'price' => 100]);

        $this->actingAs($admin)
            ->patch(route('admin.menu.items.toggle-status', $item))
            ->assertRedirect();

        $this->assertFalse($item->fresh()->is_active);

        $this->actingAs($admin)
            ->delete(route('admin.menu.items.destroy', $item))
            ->assertRedirect();

        $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
    }

    public function test_item_validation_requires_price_and_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.menu.items.store'), ['name' => 'Fiyatsız'])
            ->assertSessionHasErrors(['price', 'menu_category_id']);
    }
}
