<?php

namespace App\Http\Requests;

use App\Models\StripeSetting;
use Illuminate\Foundation\Http\FormRequest;

class StripeSettingsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $exists = StripeSetting::exists();

        return [
            'stripe_key' => ['required', 'string', 'starts_with:pk_'],
            'stripe_secret' => $exists
                ? ['nullable', 'string']
                : ['required', 'string'],
            'webhook_secret' => $exists
                ? ['nullable', 'string']
                : ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'stripe_key.starts_with' => 'The publishable key must start with "pk_".',
        ];
    }
}
