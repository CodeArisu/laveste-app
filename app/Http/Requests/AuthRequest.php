<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
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
        return $this->isRegister() ? $this->registerRules() : $this->loginRules();
    }

    protected function registerRules() : array
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    protected function loginRules() : array
    {
        return [
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:8'
        ];
    }

    protected function isRegister() : bool
    {   
        if (request()->routeIs('register')) {
            return true;
        }
        
        return request()->is('register') || request()->fullUrlIs('*/register');
    }
}
