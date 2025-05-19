<?php

namespace App\Http\Requests;

use App\Enum\ResponseCode;
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
            'product_id' => 'string|unique:garments,id',
            'additional_description' => 'required|string|max:255',
            'rent_price' => 'required|numeric',
            'poster' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048|unique:garments,poster',

            'measurement' => 'required|string',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',

            'condition_id' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'poster.mimes' => 'It only accepts PNG, JPEG, JPG files',
            'measurement.required' => 'Assign size first',
        ];
    }
}
