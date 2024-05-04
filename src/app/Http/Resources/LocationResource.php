<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'street' => $this->street,
            'number' => $this->number,
            'floor' => $this->floor,
            'zipcode' => $this->zipcode,
        ];
    }
}
