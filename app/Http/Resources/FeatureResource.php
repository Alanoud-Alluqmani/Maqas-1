<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'feature id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'feature_store_id' => $this->pivot?->id,
            'store' => $this->whenLoaded('stores'),
            'Product Category' => $this->whenLoaded('product_category')
        ];
    }
}
