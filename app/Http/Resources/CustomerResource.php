<?php

namespace App\Http\Resources;

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
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'phone' => $this->phone,
            // 'locations' => CustomerLocationCollection::make($this->whenLoaded('locations')),
           'locations' => $this->whenLoaded('locations')

            // public function locations(){
    //     return $this->hasMany(CustomerLocation::class);
    // }

    // public function measures(){
    //     return $this->hasMany(Measure::class);
    // } 

    // public function orders(){
    //     return $this->hasMany(Order::class);
    // }

    // public function ratings(){
    //     return $this->hasManyThrough(Rating::class, Order::class);
    // }
        ];
    }
}
