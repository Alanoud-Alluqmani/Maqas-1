<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

   // These rules ensure the data provided in the form meets specific requirements.
    public function rules(): array
    {
        return [
            'status_ar' => 'nullable|string|max:255|unique:statuses,status_ar',
            'status_en' => 'nullable|string|max:255|unique:statuses,status_en',
            'service_id_1' => 'sometimes|boolean',
            'service_id_2' => 'sometimes|boolean',
        ];
    }
}
