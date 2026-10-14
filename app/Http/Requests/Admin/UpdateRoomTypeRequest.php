<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends StoreRoomTypeRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['slug'] = ['required', 'string', 'max:255', Rule::unique('room_types', 'slug')->ignore($this->route('roomType'))];

        return $rules;
    }
}
