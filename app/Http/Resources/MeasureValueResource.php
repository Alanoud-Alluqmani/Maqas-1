<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasureValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'measure_id' =>$this->measure_id,
            'measure' => $this->measure,
            'measure_name' => new MeasureNameResource($this->whenLoaded('measure_name')),
            // 'locations' => CustomerLocationResource::collection($this->whenLoaded('locations')),
        // 'measure_value_id' => $this->measure_value_id,
        // 'measure v' => $this->measure,
        //  'measure' => new MeasureResource($this->whenLoaded('measure_id')),

        
        ];
    }
}
