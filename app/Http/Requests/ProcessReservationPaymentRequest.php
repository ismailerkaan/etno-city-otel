<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProcessReservationPaymentRequest extends FormRequest
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
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits_between:13,19'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:'.now()->year, 'max:'.now()->addYears(20)->year],
            'security_code' => ['required', 'digits_between:3,4'],
        ];
    }

    /** @return array<int, callable(Validator): void> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if (! $validator->errors()->has('card_number') && ! $this->passesLuhnCheck($this->string('card_number')->toString())) {
                $validator->errors()->add('card_number', 'Kart numarası geçerli değil.');
            }

            if ($validator->errors()->hasAny(['expiry_month', 'expiry_year'])) {
                return;
            }

            $expiresAt = now()->setDate($this->integer('expiry_year'), $this->integer('expiry_month'), 1)->endOfMonth();

            if ($expiresAt->isPast()) {
                $validator->errors()->add('expiry_month', 'Kartın son kullanma tarihi geçmiş.');
            }
        }];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'card_number' => preg_replace('/\D/', '', $this->string('card_number')->toString()),
            'expiry_month' => preg_replace('/\D/', '', $this->string('expiry_month')->toString()),
            'expiry_year' => preg_replace('/\D/', '', $this->string('expiry_year')->toString()),
            'security_code' => preg_replace('/\D/', '', $this->string('security_code')->toString()),
        ]);
    }

    private function passesLuhnCheck(string $cardNumber): bool
    {
        $sum = 0;
        $shouldDouble = false;

        for ($index = strlen($cardNumber) - 1; $index >= 0; $index--) {
            $digit = (int) $cardNumber[$index];

            if ($shouldDouble) {
                $digit *= 2;
                $digit = $digit > 9 ? $digit - 9 : $digit;
            }

            $sum += $digit;
            $shouldDouble = ! $shouldDouble;
        }

        return $sum > 0 && $sum % 10 === 0;
    }
}
