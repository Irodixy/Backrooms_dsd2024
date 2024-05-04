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
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'imgName' => $this->imgName,
            'costumerVisits' => $this->costumerVisits,
            'store' => new StoreResource($this->whenLoaded('store'))
        ];
    }
}
