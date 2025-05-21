<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecifyProductRequest extends FormRequest
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
            'id' => 'required|integer|exists:features,id'
        ];
    }

}