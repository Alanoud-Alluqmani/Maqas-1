<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'order_id' => $this->id,
        'total_price' => $this->total_price,
        'items' => ItemResource::collection($this->whenLoaded('items')),
        'customer' => $this->whenLoaded('customer'),
        'customer_location' => new CustomerLocationResource($this->whenLoaded('customer_location')),
        'store' => $this->whenLoaded('store'),
        'store_location' => new CustomerLocationResource($this->whenLoaded('store_location')),
        'rating' => $this->whenLoaded('rating'),
        'status' => $this->whenLoaded('status'),
        'service' => $this->whenLoaded('service'),
        ];
    }
}
