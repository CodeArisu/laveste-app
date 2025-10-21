<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'contact' => 'required|string|digits:11|unique:user_details,contact',
            'address' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Fill in required first name',
            'first_name.max' => 'Max name length of 25',
            'last_name.required' => 'Fill in required last name',
            'last_name.max' => 'Max name length of 25',
            'address.required' => 'Address is required',
            'contact.required' => 'The contact number is required',
            'contact.digits' => 'The contact number must be 11 digits',
            'contact.unique' => 'The contact number should be unique',
        ];
    }
}
