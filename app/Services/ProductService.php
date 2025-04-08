<?php

namespace App\Services;

use App\Exceptions\InternalException;
use App\Http\Requests\ProductRequest;
use App\Models\{Product, ProductType, Supplier, Type, Subtype};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{DB, Log};

class ProductService
{
    public function requestCreateProduct(ProductRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $products = $this->createProduct($request);

                if (empty($products)) {
                    throw new \RuntimeException('No products were created');
                }

                // Validate all products were created successfully
                foreach ($products as $product) {
                    if (!Product::exists()) {
                        throw new \RuntimeException("Failed to create {$product}");
                    }
                }

                return ['product' => array_keys($products), 'message' => 'Product added successfully'];
            });
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['product' => $products, 'message' => 'Failed to add product'];
        }
    }

    public function requestUpdateProduct(ProductRequest $request, Product $product)
    {
        try {
            return DB::transaction(function () use ($request, $product) {
                $updatedProducts = $this->updateProduct($request, $product);

                $this->validateUpdateResults($updatedProducts);

                return [
                    'product' => $product->fresh(),
                    'message' => 'Product updated successfully',
                ];
            });
        } catch (\Exception $e) {
            Log::error("Product update failed - ID: {$product->id}", ['error' => $e->getMessage(), 'request' => $request->validated(),]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['product' => $products, 'message' => 'Failed to update product'];
        }
    }

    public function requestDeleteProduct(Product $product)
    {
        $productName = $product->product_name;
        try {
            $product->deleteOrFail();

            return [
                'message' => 'Product Deleted Successfully',
                'product' => $productName,
            ];
        } catch (\Exception $e) {
            Log::error("Product delete failed - ID: {$product->id}", ['error' => $e->getMessage()]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['product' => $productName, 'message' => 'Failed to delete product'];
        }
    }

    private function createProduct(ProductRequest $request): array
    {
        $validated = $request->safe();

        $supplier = $this->handleSupplier($validated->only(['supplier_name', 'company_name', 'address', 'contact']));

        $product = $this->handleProduct($validated->only(['product_name', 'original_price', 'description']), [
            'supplier_id' => $supplier->id,
        ]);

        $productType = $this->handleProductType($validated->only(['type', 'subtype']), [
            'product_id' => $product->id
        ]);

        return compact('supplier', 'productType', 'product');
    }

    private function updateProduct(ProductRequest $request, Product $product): array
    {
        $validated = $request->safe();

        // Update supplier (unchanged)
        $supplier = $this->updateOrKeepSupplier($product->supplier, $validated->only(['supplier_name', 'company_name', 'address', 'contact']));

        // Handle product types
        $typeData = array_merge($validated->only(['type', 'subtype']), ['product_id' => $product]);
        if ($this->typeDataChanged($product, $typeData)) {
            $productTypes = $this->updateOrKeepProductTypes($product, $typeData);

            // Refresh the relationship
            $product->load('productType');

            // Get primary type (first or only one)
            $primaryProductType = $product->productType->first();
        } else {
            $primaryProductType = $product->productType;
        }

        $product = $this->updateProductDetails($product, $validated->only(['product_name', 'original_price', 'description']), ['supplier_id' => $supplier->id]);

        return compact('supplier', 'primaryProductType', 'product');
    }

    private function handleProduct(array $productData, array $relations): Product
    {
        // creates new product if not exists
        return Product::firstOrCreate([
                'product_name' => $productData['product_name'],
                'supplier_id' => $relations['supplier_id'],
            ],[
                'original_price' => $productData['original_price'],
                'description' => $productData['description'],
            ],
        );
    }

    private function updateProductDetails(Product $product, array $productData, array $relations): Product
    {
        // Update product details
        $product->update([
            'product_name' => $productData['product_name'],
            'original_price' => $productData['original_price'],
            'description' => $productData['description'],
            'supplier_id' => $relations['supplier_id'],
        ]);

        return $product->fresh();
    }

    private function handleSupplier(array $supplierData): Supplier
    {
        // creates new supplier if not exists
        return Supplier::updateOrCreate(
            [
                'supplier_name' => $supplierData['supplier_name'],
                'company_name' => $supplierData['company_name'],
            ],
            [
                'address' => $supplierData['address'],
                'contact' => $supplierData['contact'],
            ],
        );
    }

    private function updateOrKeepSupplier(?Supplier $supplier, array $supplierData): Supplier
    {
        if (!$supplier || $this->supplierDataChanged($supplier, $supplierData)) {
            return $this->handleSupplier($supplierData);
        }

        return $supplier;
    }

    private function supplierDataChanged(Supplier $supplier, array $supplierData): bool
    {
        return $supplier->supplier_name !== $supplierData['supplier_name'] || $supplier->company_name !== $supplierData['company_name'] || $supplier->address !== $supplierData['address'] || $supplier->contact !== $supplierData['contact'];
    }

    private function handleProductType(array $typeData, array $relations): ProductType|Collection
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
                    'product_id' => $relations['product_id'],
                ]),
            );
        }
        return $productTypes->count() === 1 ? $productTypes->first() : $productTypes;
    }

    private function updateOrKeepProductTypes(Product $product, array $typeData): array
    {
        $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);

        $subtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];

        // Get existing product types for this product
        $existingTypes = $product->productType->with(['type', 'subtype'])->get();

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

        // Add new types
        $newProductTypes = collect();
        foreach ($subtypesToAdd as $subtypeName) {
            $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);
            $productType = ProductType::firstOrCreate([
                'type_id' => $mainType->id,
                'subtype_id' => $subType->id,
                'product_id' => $product->id,
            ]);

            $newProductTypes->push($productType);
        }

        // Return all current product types (kept + new)
        return $product->productType
            ->whereIn('subtype_id', Subtype::whereIn('subtype_name', $subtypes)->pluck('id'))
            ->get()
            ->toArray();
    }

    private function typeDataChanged(Product $product, array $typeData): bool
    {
        $currentMainType = $product->productType->first()->type->type_name ?? null;
        // dd($product->productType->with('subtype')->get());
        $currentSubtypes = $product->productType
            ->with('subtype')
            ->get()
            ->map(function ($productType) {
                return $productType->subtype->subtype_name ?? null;
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $newSubtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];
        sort($currentSubtypes);
        sort($newSubtypes);

        return $currentMainType !== $typeData['type'] || $currentSubtypes !== $newSubtypes;
    }

    protected function validateUpdateResults(array $updatedData): void
    {
        foreach ($updatedData as $field => $success) {
            if (!$success) {
                throw new \RuntimeException("Failed to update {$field}");
            }
        }
    }
}