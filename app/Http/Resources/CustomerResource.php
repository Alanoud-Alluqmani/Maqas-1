<?php

namespace App\Http\Resources;

use App\Models\CustomerLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'customer_id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'phone' => $this->phone,
           'locations' => CustomerLocationResource::collection($this->whenLoaded('locations')),
        ];
    }
}
