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
            'contact' => 'required|string',
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

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data request',
            'details' => $errors->messages(),
        ], ResponseCode::INVALID->value);

        throw new HttpResponseException($response);
    }
}