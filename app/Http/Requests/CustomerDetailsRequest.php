<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerDetailsRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'contact' => 'required|string',
            'address' => 'required|string|max:255',
            'email' => 'sometimes|string',

            'venue' => 'nullable|string',
            'event_data' => 'nullable|date',
            'reason_for_renting' => 'nullable|string',

            'pickup_date' => 'required|date',
            'rented_date' => 'required|date',
            'return_date' => 'required|date',
        ];
    }
}