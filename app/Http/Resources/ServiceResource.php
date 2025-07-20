<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'service_id' => $this->id,
        'name_ar' => $this->name_ar,
        'name_en' => $this->name_en,
        'description_ar' => $this->description_ar,
        'description_en' => $this->description_en,
        'icon_id' => $this->icon_id,
        'stores' => $this->whenLoaded('stores'),
        'orders' => $this->whenLoaded('orders'),
       ];
    }
}
