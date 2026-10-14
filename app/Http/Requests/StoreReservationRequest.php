<?php

namespace App\Http\Requests;

use App\Models\RoomType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check_in' => ['required', Rule::date()->format('Y-m-d')->todayOrAfter()],
            'check_out' => ['required', Rule::date()->format('Y-m-d')->after('check_in')],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['required', 'integer', 'min:0', 'max:20'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'terms_accepted' => ['accepted'],
        ];
    }

    /** @return array<int, callable(Validator): void> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['adults', 'children'])) {
                return;
            }

            $roomType = $this->route('roomType');

            if (! $roomType instanceof RoomType || ! $roomType->is_active) {
                $validator->errors()->add('room_type', 'Seçilen oda tipi rezervasyona açık değil.');

                return;
            }

            if ($this->integer('adults') > $roomType->max_adults) {
                $validator->errors()->add('adults', 'Bu oda tipi en fazla '.$roomType->max_adults.' yetişkin kabul eder.');
            }

            if ($this->integer('children') > $roomType->max_children) {
                $validator->errors()->add('children', 'Bu oda tipi en fazla '.$roomType->max_children.' çocuk kabul eder.');
            }
        }];
    }
}
