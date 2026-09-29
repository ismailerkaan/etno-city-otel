<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_testimonials(): void
    {
        $this->get(route('admin.testimonials.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_testimonials_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.testimonials.index'))
            ->assertOk()
            ->assertSee('Misafir Yorumları')
            ->assertSee('Yorumlar Bölüm Başlığı ve Açıklaması');
    }

    public function test_admin_can_update_testimonials_section_headers(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::query()->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.testimonials.section-update'), [
                'testimonials_eyebrow' => 'Misafir Notları',
                'testimonials_title' => 'Unutulmaz Anılar',
                'testimonials_description' => 'Mükemmel bir tatil deneyimi sunuyoruz.',
                'testimonials_eyebrow_en' => 'Guest Notes',
                'testimonials_title_en' => 'Unforgettable Memories',
                'testimonials_description_en' => 'Delivering serene hospitality.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('site_settings', [
            'id' => 1,
            'testimonials_eyebrow' => 'Misafir Notları',
            'testimonials_title' => 'Unutulmaz Anılar',
            'testimonials_description' => 'Mükemmel bir tatil deneyimi sunuyoruz.',
            'testimonials_eyebrow_en' => 'Guest Notes',
            'testimonials_title_en' => 'Unforgettable Memories',
            'testimonials_description_en' => 'Delivering serene hospitality.',
        ]);
    }

    public function test_admin_can_create_testimonial(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.testimonials.store'), [
                'author_name' => 'Elif Yılmaz',
                'author_title' => 'İstanbul, Türkiye · Deniz Süiti',
                'author_title_en' => 'Istanbul, Turkey · Sea Suite',
                'comment' => 'Harika bir sükunet ve lüks anlayışı.',
                'comment_en' => 'Wonderful serenity and true luxury.',
                'rating' => 5,
                'sort_order' => 10,
                'is_active' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('testimonials', [
            'author_name' => 'Elif Yılmaz',
            'author_title' => 'İstanbul, Türkiye · Deniz Süiti',
            'comment' => 'Harika bir sükunet ve lüks anlayışı.',
            'rating' => 5,
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_testimonial(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $testimonial = Testimonial::factory()->create([
            'author_name' => 'Eski İsim',
            'comment' => 'Eski yorum',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.testimonials.update', $testimonial), [
                'author_name' => 'Yeni İsim',
                'author_title' => 'Ankara · Ufuk Odası',
                'author_title_en' => 'Ankara · Horizon Room',
                'comment' => 'Yeni güncellenmiş yorum.',
                'comment_en' => 'New updated comment.',
                'rating' => 4,
                'sort_order' => 2,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.testimonials.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'author_name' => 'Yeni İsim',
            'comment' => 'Yeni güncellenmiş yorum.',
            'rating' => 4,
        ]);
    }

    public function test_admin_can_toggle_testimonial_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $testimonial = Testimonial::factory()->create(['is_active' => true]);

        $this->actingAs($admin)
            ->patch(route('admin.testimonials.toggle-status', $testimonial))
            ->assertRedirect();

        $this->assertFalse($testimonial->fresh()->is_active);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $testimonial = Testimonial::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.testimonials.destroy', $testimonial))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    public function test_home_page_displays_custom_testimonials_title_and_active_reviews(): void
    {
        SiteSetting::query()->firstOrFail()->update([
            'testimonials_eyebrow' => 'Misafir Hatıraları',
            'testimonials_title' => 'Eşsiz Huzur Deneyimi',
            'testimonials_description' => 'Sakin koylarımızda geçen benzersiz anlar.',
        ]);

        Testimonial::factory()->create([
            'author_name' => 'Canan Özdemir',
            'comment' => 'Ruhumu dinlendirdiğim en iyi tatildi.',
            'is_active' => true,
        ]);

        Testimonial::factory()->create([
            'author_name' => 'Pasif Kullanıcı',
            'comment' => 'Görünmemeli.',
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Misafir Hatıraları')
            ->assertSee('Eşsiz Huzur Deneyimi')
            ->assertSee('Sakin koylarımızda geçen benzersiz anlar.')
            ->assertSee('Canan Özdemir')
            ->assertSee('Ruhumu dinlendirdiğim en iyi tatildi.')
            ->assertDontSee('Pasif Kullanıcı');
    }
}
