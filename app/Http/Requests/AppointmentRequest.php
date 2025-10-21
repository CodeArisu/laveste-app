<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'contact' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'email' => 'sometimes|string',

            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'contact.required' => 'Contact is required',
            'address.required' => 'Address is required',

            'appointment_date.required' => 'Appointment date is required',
            'appointment_time.required' => 'Appointment time is required',
        ];
    }
}
