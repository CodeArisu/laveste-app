<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'supplier_name' => $this->supplier_name,
            'contact' => $this->contact,
            'company_name' => $this->company_name ?? 'no name'
        ];  
    }
}
