<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [                     // Validates the password against multiple rules
                'required',                     // Password is required
                'confirmed',
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
