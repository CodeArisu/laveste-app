<?php

namespace App\Http\Requests;

use App\Enum\ResponseCode;
use App\Exceptions\AuthException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function authenticate()
    {
        if (!Auth::attempt($this->safe()->only(['email', 'password']))) {
            Log::warning('Invalid Credentials');
            throw AuthException::invalidUserCredentials();
            return false;
        }

        return true;
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
