<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'loc_id' => $this->id,
            'loc_url' => $this->loc_url,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
