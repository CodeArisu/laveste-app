<?php

namespace App\DTOs;

class ProductDTO
{   
    public array $productData, $supplierData, $categoricalData;

    public function __construct(array $data)
    {
        $this->productData = $this->filterProductData($data);
        $this->supplierData = $this->filterSupplierData($data);
        $this->categoricalData = $this->filterCategoryData($data);
    }

    private function filterProductData(array $data)
    {
        return [
            'product_name' => $data['product_name'] ?? null,
            'original_price' => $data['original_price'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    }

    private function filterSupplierData(array $data)
    {
        return [
            'supplier_name' => $data['supplier_name'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'address' => $data['address'] ?? null,
            'contact' => $data['contact'] ?? null,
        ];
    }

    private function filterCategoryData(array $data)
    {
        return [
            'type' => $data['type'] ?? null,
            'subtype' => $data['subtype'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
