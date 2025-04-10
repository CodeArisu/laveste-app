<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{   
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        return [
            'product_name' => $this->product_name,
            'original_price' => $this->original_price,
            'description' => $this->description,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'type' => $this->productTypes->first() ? new TypeResource($this->productTypes->first()->type) : null, 
            'subtypes' => SubtypeResource::collection($this->whenLoaded('subtypes')) ?? null,
            'added_at' => $this->created_at,
        ];
    }
}
