<?php

namespace App\Http\Requests;

use App\Enum\StatusCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GarmentRequest extends FormRequest
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
            'product_id' => 'string|unique:products,id',
            'additional_description' => 'required|string|max:255',
            'rent_price' => 'required|numeric',
            'poster' => 'required|unique:garments,poster',

            'measurement' => 'required|string',
            'length' => 'required|numeric',
            'width' => 'required|numeric',

            'condition_id' => 'nullable|integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data request',
            'details' => $errors->messages(),
        ], StatusCode::INVALID->value);

        throw new HttpResponseException($response);
    }
}
