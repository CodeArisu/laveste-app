<?php

namespace App\Http\Resources;

use App\Models\ProductType;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{   
    public $preserveKeys = true;
    public static $wrap = "products";
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
            'supplier' => new SupplierResource(Supplier::find($this->supplier_id)),
            'product_type' => new ProductTypeResource(ProductType::where('product_id', $this->id)->first()),
            
        ];
    }
}
