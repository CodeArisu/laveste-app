<?php

namespace App\Services;

use App\Http\Requests\ProductRequest;
use App\Models\{Product, ProductType, Supplier, Type, Subtype};
use Illuminate\Support\Collection;

class ProductService
{   
    public function createProduct(ProductRequest $request) : array
    {
        $validated = $request->safe();

        $supplier = $this->handleSupplier($validated->only([
            'supplier_name', 'company_name', 'address', 'contact'
        ]));

        $product = $this->handleProduct($validated->only([
            'product_name', 'original_price', 'description'
        ]), 
        [ 'supplier_id' => $supplier->id]);

        $productType = $this->handleProductType($validated->only([
            'type', 'subtype'
        ]), ['product_id' => $product->id]);

        return compact('supplier', 'productType', 'product');
    }

    public function updateProduct(ProductRequest $request, Product $product) : array
    {
        $validated = $request->safe();

        // Update supplier (unchanged)
        $supplier = $this->updateOrKeepSupplier(
            $product->supplier, 
            $validated->only(['supplier_name', 'company_name', 'address', 'contact'])
        );
    
        // Handle product types
        $typeData = $validated->only(['type', 'subtype']);
        if ($this->typeDataChanged($product, $typeData)) {
            $productTypes = $this->updateOrKeepProductTypes($product, $typeData);
            
            $productTypes = $productTypes instanceof Collection ? $productTypes : collect([$productTypes]);
            $product->productTypes()->sync($productTypes->pluck('id'));
            
            $primaryProductType = $productTypes->first();
        } else {

            $primaryProductType = $product->productType;
        }
    
        $product = $this->updateProductDetails(
            $product,
            $validated->only(['product_name', 'original_price', 'description']),
            [
                'supplier_id' => $supplier->id,
                'product_type_id' => $primaryProductType->id
            ]
        );
    
        return compact('supplier', 'productType', 'product');
    }
    
    private function handleProduct(array $productData, array $relations) : Product
    {   
        // creates new product if not exists
        return Product::firstOrCreate([ 
            'product_name' => $productData['product_name'],
            'supplier_id' => $relations['supplier_id'], 
        ], [
            'original_price' => $productData['original_price'],
            'description' => $productData['description']
        ]);
    }

    private function updateProductDetails(Product $product, array $productData, array $relations) : Product
    {   
        // Update product details
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
        // creates new supplier if not exists
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
        return $supplier->supplier_name !== $supplierData['supplier_name'] || $supplier->company_name !== $supplierData['company_name'] ||
               $supplier->address !== $supplierData['address'] || $supplier->contact !== $supplierData['contact'];
    }

    private function handleProductType(array $typeData, array $relations) : ProductType|Collection
    {   
        $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);
        $subtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];

        $productTypes = collect();
        foreach ($subtypes as $subtypeName) {
            $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);
            $productTypes->push(
                ProductType::firstOrCreate([
                    'type_id' => $mainType->id,
                    'subtype_id' => $subType->id,
                    'product_id' => $relations['product_id']
                ])
            );
        }
        return $productTypes->count() === 1 ? $productTypes->first() : $productTypes;
    }

    private function updateOrKeepProductTypes(Product $product, array $typeData) : array
    {
    $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);
    $subtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];

    // Get existing product types for this product
    $existingTypes = $product->productTypes()->with(['type', 'subtype'])->get();
    
    // Determine which subtypes to keep, add, and remove
    $subtypesToKeep = [];
    $subtypesToAdd = $subtypes;
    
    foreach ($existingTypes as $existingType) {
        if (in_array($existingType->subtype->subtype_name, $subtypes)) {
            // Keep this relationship
            $subtypesToKeep[] = $existingType->subtype->subtype_name;
            $subtypesToAdd = array_diff($subtypesToAdd, [$existingType->subtype->subtype_name]);
        }
    }
    
    // Remove types that are no longer needed
    $typesToRemove = $existingTypes->reject(function ($type) use ($subtypes) {
        return in_array($type->subtype->subtype_name, $subtypes);
    });
    
    if ($typesToRemove->isNotEmpty()) {
        $product->productTypes()->detach($typesToRemove->pluck('id'));
    }
    
    // Add new types
    $newProductTypes = collect();
    foreach ($subtypesToAdd as $subtypeName) {
        $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);
        $productType = ProductType::firstOrCreate([
            'type_id' => $mainType->id,
            'subtype_id' => $subType->id,
            'product_id' => $product->id
        ]);
        
        $product->productTypes()->syncWithoutDetaching([$productType->id]);
        $newProductTypes->push($productType);
    }
    
    // Return all current product types (kept + new)
    return $product->productTypes()
        ->whereIn('subtype_id', Subtype::whereIn('subtype_name', $subtypes)->pluck('id'))
        ->get();
    }

    private function typeDataChanged(Product $product, array $typeData): bool
    {   
        $currentMainType = $product->productType->first()->type->type_name ?? null;
        $currentSubtypes = $product->productType->pluck('subtype.subtype_name')->unique()->toArray();
        
        $newSubtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];
        sort($currentSubtypes);
        sort($newSubtypes);
        
        return $currentMainType !== $typeData['type'] || $currentSubtypes !== $newSubtypes;
    }
}