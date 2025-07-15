<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'design id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'price' => $this->price, 
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'feature_store' => new FeatureStoreResource($this->whenLoaded('feature_store')),
     
        ];

    }
}
