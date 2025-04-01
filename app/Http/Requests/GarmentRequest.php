<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'product_id' => 'required|integer|unique:garments,product_id',
            'additional_description' => 'required|string|max:255',
            'rent_price' => 'required|numeric',
            'poster' => 'required|unique:garments,poster',

            'measurement' => 'required|string',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
        ];
    }
}
