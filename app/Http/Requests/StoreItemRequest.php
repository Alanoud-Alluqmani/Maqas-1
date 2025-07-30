<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Return "true" to allow all users to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        //'order_id' => 'required|integer|exists:orders,id',
        'design_id' => 'required|integer|exists:designs,id',
        'measure_id' => 'nullable|integer|exists:measures,id',
      
        ];
    }

}