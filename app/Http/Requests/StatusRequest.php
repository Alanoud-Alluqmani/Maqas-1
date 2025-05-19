<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
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
            'status_ar' => 'required|string|max:255|unique:statuses,status_ar',
            'status_en' => 'required|string|max:255|unique:statuses,status_en',
            'service_id_1' => 'required|boolean',
            'service_id_2' => 'required|boolean',
        ];
    }

}