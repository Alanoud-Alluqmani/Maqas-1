<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255', // Validates that "name" is required, a string, and no longer than 255 characters
           // 'name_ar' => 'required|string|max:255|regex:/^[\p{Arabic} ]+$/u', // Must be Arabic
           // 'name_en' => 'required|string|max:255|regex:/^[A-Za-z ]+$/',
            'phone' => 'required', // |digits:10'
            'email' => 'required|email|unique:users',        // Validates that "email" is required and must follow email format
            'password' => [                     // Validates the password against multiple rules
                'required',                     // Password is required
                'string',                       // Must be a string
                'min:8',                        // Minimum length of 8 characters
                'max:24',                       // Maximum length of 24 characters
                'regex:/[A-Z]/',                // Must include at least one uppercase letter
                'regex:/[a-z]/',                // Must include at least one lowercase letter
                'regex:/[0-9]/',                // Must include at least one numeric character
                'regex:/[@$!%*?&#]/',           // Must include at least one special character
            ],
        ];
    }
}
