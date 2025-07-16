<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
        'feature_store_id' => $this->id,
          'feature_id' => $this->feature_id,
        // 'feature_id' => FeatureResource::collection($this->features),
        // 'feature_id' => new FeatureResource($this->feature),
        'store_id' => $this->store_id,
    ];

    }
}
