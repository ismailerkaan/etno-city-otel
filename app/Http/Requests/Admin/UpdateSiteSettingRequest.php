<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:4096'],
            'remove_logo' => ['sometimes', 'boolean'],
            'instagram_url' => ['nullable', 'url', 'max:2048'],
            'whatsapp_url' => ['nullable', 'url', 'max:2048'],
            'facebook_url' => ['nullable', 'url', 'max:2048'],
            'youtube_url' => ['nullable', 'url', 'max:2048'],
            'tripadvisor_url' => ['nullable', 'url', 'max:2048'],
            'hero_eyebrow' => ['nullable', 'string', 'max:255'],
            'hero_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_title_en' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:2000'],
            'hero_subtitle_en' => ['nullable', 'string', 'max:2000'],
            'hero_image_url' => ['nullable', 'url', 'max:2048'],
            'about_eyebrow' => ['nullable', 'string', 'max:255'],
            'about_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'about_title' => ['required', 'string', 'max:255'],
            'about_title_en' => ['nullable', 'string', 'max:255'],
            'about_primary_text' => ['nullable', 'string', 'max:5000'],
            'about_primary_text_en' => ['nullable', 'string', 'max:5000'],
            'about_secondary_text' => ['nullable', 'string', 'max:5000'],
            'about_secondary_text_en' => ['nullable', 'string', 'max:5000'],
            'about_image_url' => ['nullable', 'url', 'max:2048'],
            'suite_count' => ['required', 'integer', 'min:0', 'max:999'],
            'newsletter_title' => ['nullable', 'string', 'max:255'],
            'newsletter_title_en' => ['nullable', 'string', 'max:255'],
            'newsletter_text' => ['nullable', 'string', 'max:2000'],
            'newsletter_text_en' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
