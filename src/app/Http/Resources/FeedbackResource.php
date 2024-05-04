<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'store' => new StoreResource($this->whenLoaded('store')),
            'item' => new ItemResource($this->whenLoaded('item')),
            'comment' => $this->comment,
            'rating' => $this->rating,
            'date' => $this->date
        ];
    }
}
