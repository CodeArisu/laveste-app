<?php

namespace App\Http\Requests;

use App\Enum\ResponseCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            'product_name' => 'required|string|max:50',
            'original_price' => 'required|numeric',
            'description' => 'nullable|max:255',

            'supplier_name' => 'required|string|max:65',
            'company_name' => 'nullable|string|max:65',
            'address' => 'nullable|string|max:255',
            'contact' => 'required|integer|digits:10',

            'type' => 'required|string',
            'subtype' => 'required|array|prohibited_if:subtype.*,string',
            'subtype.*' => 'required|string',
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
