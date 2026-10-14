<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRoomTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('room_types', 'slug')],
            'description' => ['nullable', 'string', 'max:3000'],
            'description_en' => ['nullable', 'string', 'max:3000'],
            'nightly_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'max_adults' => ['required', 'integer', 'min:1', 'max:20'],
            'max_children' => ['required', 'integer', 'min:0', 'max:20'],
            'size_sqm' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'bed_type' => ['nullable', 'string', 'max:255'],
            'bed_type_en' => ['nullable', 'string', 'max:255'],
            'features_text' => ['nullable', 'string', 'max:3000'],
            'features_en_text' => ['nullable', 'string', 'max:3000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
            'show_on_home' => ['sometimes', 'boolean'],
            'images' => ['nullable', 'array', 'max:12'],
            'images.*.id' => ['nullable', 'integer', 'exists:room_images,id'],
            'images.*.file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'images.*.alt_text_en' => ['nullable', 'string', 'max:255'],
            'images.*.is_primary' => ['sometimes', 'boolean'],
            'images.*.remove' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'is_active' => $this->boolean('is_active'),
            'show_on_home' => $this->boolean('show_on_home'),
        ]);
    }
}
