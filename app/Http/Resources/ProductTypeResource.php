<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type ? new TypeResource($this->whenLoaded('type')) : null,
            'subtypes' => $this->subtype ? new SubtypeResource($this->whenLoaded('subtype')) : [],
        ];
    }
}
