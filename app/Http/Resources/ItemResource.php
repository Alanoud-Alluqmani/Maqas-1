<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'item_id' => $this->id,
        'order_id' => $this->order_id,
        // 'order' => OrderResource::collection($this->whenLoaded('order')),
        'measure' => new MeasureResource($this->whenLoaded('measure')),
        'designs' => DesignResource::collection($this->whenLoaded('designs')),

        ];
    }
}
