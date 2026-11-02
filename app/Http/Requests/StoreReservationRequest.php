<?php

namespace App\Http\Requests;

use App\Models\RoomType;
use App\Services\Hms\HmsClient;
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
        $hmsRules = app(HmsClient::class)->isEnabled() ? [
            'rate_plan_id' => ['required', 'integer', 'min:1'],
            'price_key' => ['required', 'string', 'regex:/^\d+-[01]$/'],
            'child_ages' => [Rule::requiredIf($this->integer('children') > 0), 'array', 'size:'.max(0, $this->integer('children'))],
            'child_ages.*' => ['integer', 'between:0,16'],
        ] : [];

        return [
            ...$hmsRules,
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

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'child_ages.required' => 'Lütfen her çocuk için yaş seçin.',
            'child_ages.size' => 'Lütfen her çocuk için yaş seçin.',
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
