<?php

namespace App\Services;

use App\Http\Requests\ProductRequest;
use App\Models\{Product, ProductType, Supplier, Type, Subtype};

class ProductService
{   
    public function createProduct(ProductRequest $request) : array
    {
        $validated = $request->safe();

        $supplier = $this->handleSupplier($validated->only([
            'supplier_name', 'company_name', 'address', 'contact'
        ]));

        $productType = $this->handleProductType($validated->only([
            'type', 'subtype'
        ]));

        $product = $this->handleProduct($validated->only([
            'product_name', 'original_price', 'description'
        ]), [
            'supplier_id' => $supplier->id,
            'product_type_id' => $productType->id
        ]);

        return compact('supplier', 'productType', 'product');
    }

    public function updateProduct(ProductRequest $request, Product $product) : array
    {
        $validated = $request->safe();

        $supplier = $this->updateOrKeepSupplier(
            $product->supplier, $validated->only([
            'supplier_name', 'company_name', 'address', 'contact'
        ]));

        $productType = $this->updateOrKeepProductType(
            $product->productType, $validated->only([
            'type', 'subtype'
        ]));

        $product = $this->updateProductDetails(
            $product,
            $validated->only(['product_name', 'original_price', 'description']),
            [
                'supplier_id' => $supplier->id,
                'product_type_id' => $productType->id
            ]
        );

        return compact('supplier', 'productType', 'product');
    }
    
    private function handleProduct(array $productData, array $relations) : Product
    {
        return Product::firstOrCreate([ 
            'product_name' => $productData['product_name'],
            'supplier_id' => $relations['supplier_id'], 
        ], [
            'original_price' => $productData['original_price'],
            'description' => $productData['description'],
            'product_type_id' => $relations['product_type_id']
        ]);
    }

    private function updateProductDetails(Product $product, array $productData, array $relations) : Product
    {
        $product->update([
            'product_name' => $productData['product_name'],
            'original_price' => $productData['original_price'],
            'description' => $productData['description'],
            'supplier_id' => $relations['supplier_id'],
            'product_type_id' => $relations['product_type_id']
        ]);

        return $product->fresh();
    }
    
    private function handleSupplier(array $supplierData) : Supplier
    {   
       return Supplier::firstOrCreate([
            'supplier_name' => $supplierData['supplier_name'],
            'company_name' => $supplierData['company_name'],
       ], [
            'address' => $supplierData['address'],
            'contact' => $supplierData['contact']
       ]);
    }

    private function updateOrKeepSupplier(?Supplier $supplier, array $supplierData) : Supplier
    {
        if (!$supplier || $this->supplierDataChanged($supplier, $supplierData)) {
            return $this->handleSupplier($supplierData);
        }

        return $supplier;
    }

    private function supplierDataChanged(Supplier $supplier, array $supplierData): bool
    {
        return $supplier->supplier_name !== $supplierData['supplier_name'] ||
               $supplier->company_name !== $supplierData['company_name'] ||
               $supplier->address !== $supplierData['address'] ||
               $supplier->contact !== $supplierData['contact'];
    }

    private function handleProductType(array $typeData) : ProductType
    {
        $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);
        $subType = Subtype::firstOrCreate(['subtype_name' => $typeData['subtype']]);

        return ProductType::firstOrCreate([
            'type_id' => $mainType->id,
            'subtype_id' => $subType->id
        ]);
    }

    private function updateOrKeepProductType(?ProductType $productType, array $typeData) : ProductType
    {
        if (!$productType || $this->typeDataChanged($productType, $typeData)) {
            return $this->handleProductType($typeData);
        }

        return $productType;
    }

    private function typeDataChanged(ProductType $productType, array $typeData): bool
    {
        return $productType->type->type_name !== $typeData['type'] ||
               $productType->subtype->subtype_name !== $typeData['subtype'];
    }
}