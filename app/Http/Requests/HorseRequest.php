<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorseRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'facts' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'breed' => ['nullable', 'string', 'max:255'],
            'colour' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:Mare,Gelding,Stallion'],
            'height_hands' => ['nullable', 'numeric', 'min:5', 'max:25'],
            'arrival_date' => ['nullable', 'date', 'before_or_equal:today'],
            'personality' => ['nullable', 'string'],
            'favourite_treats' => ['nullable', 'string', 'max:255'],
            'backstory' => ['nullable', 'string'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_photos' => ['nullable', 'array'],
            'delete_photos.*' => ['integer', 'exists:horse_photos,id'],
        ];
    }
}
