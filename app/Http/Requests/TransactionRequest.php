<?php

namespace App\Http\Requests;

use App\Enum\ResponseCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
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
            'payment_method' => 'required|string',
            'payment' => 'required|numeric',
            'coupon_code' => 'nullable|string|max:8',
            'vat' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => 'Enter payment method',
            'payment.required' => 'Enter payment',
            'payment.numeric' => 'Enter only numeric number',
        ];
    }
}
