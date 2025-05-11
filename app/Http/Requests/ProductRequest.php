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
            'contact' => 'required|string|digits:11',

            'type' => 'required|string',
            'subtype' => 'required|prohibited_if:subtype.*,string',
            'subtype.*' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'The product name is required',
            'product_name.max' => 'Product name cannot exceed 255 characters',
            'original_price.required' => 'The original price is required',
            'original_price.numeric' => 'The original price must be a number',
            'description.max' => 'Description cannot exceed 255 characters',
            'supplier_name.required' => 'The supplier name is required',
            'supplier_name.max' => 'Supplier name cannot exceed 65 characters',
            'company_name.max' => 'Company name cannot exceed 65 characters',
            'address.max' => 'Address cannot exceed 255 characters',
            'contact.required' => 'The contact number is required',
            'contact.digits' => 'The contact number must be 11 digits',
            'type.required' => 'The type is required',
            'type.string' => 'The type must be a string',
            'subtype.required' => 'The subtype is required',
            'subtype.prohibited_if' => 'The subtype is required',
            'subtype.string' => 'The subtype must be a string',
            'subtype.*.required' => 'The subtype is required',
            'subtype.*.string' => 'The subtype must be a string',
        ];
    }
}
