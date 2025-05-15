<?php

namespace App\Http\Requests;

use App\Enum\ResponseCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'contact' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'email' => 'sometimes|string',

            'venue' => 'sometimes|string',
            'event_date' => 'sometimes|date',
            'reason_for_renting' => 'sometimes|string',
            'is_regular' => 'sometimes|boolean',

            'pickup_date' => 'required|date',
            'rented_date' => 'required|date',
            'return_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter customer name',
            'contact.required' => 'Please enter phone number',
            'address.required' => 'Please enter address',

            'pickup_date.required' => 'Enter pickup date',
            'rented_date.required' => 'Enter rented date',
            'return_date.required' => 'Enter return date',
        ];
    }
}