<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Return "true" to allow all users to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

   // These rules ensure the data provided in the form meets specific requirements.
    public function rules(): array
    {
        return [
            'email' => 'required|email',        // Validates that "email" is required and must follow email format
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
