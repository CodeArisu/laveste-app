<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GarmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        return [
            'product' => $this->product ? new ProductResource($this->whenLoaded('product')) : null,
            'rent_price' => $this->rent_price ?? 0,
            'additional_description' => $this->additional_description ?? null,
            'poster' => $this->poster ?? null,
            'size' => $this->size ? new SizeResource($this->whenLoaded('size')) : null,
            'condition' => $this->condition->condition_name,
        ];
    }
}
