<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{   
    public static $wrap = 'data';
    /**
     * Transform the resource into an array.
     *`
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        return [
            'product_name' => $this->product_name,
            'original_price' => $this->original_price,
            'description' => $this->description,
            'supplier' => $this->supplier ? new SupplierResource($this->whenLoaded('supplier')) : null,
            'type' => $this->whenLoaded('types', function () {
                return $this->types->first() ? [
                    'type_name' => $this->types->first()->type_name,
                    // Include other type fields if needed
                ] : null;
            }), 
            'subtypes' => SubtypeResource::collection($this->whenLoaded('subtypes')) ?? null,
            'added_at' => $this->created_at,
        ];
    }
}
