<?php

namespace App\Services;

use App\Enum\StatusCode;
use App\Exceptions\InternalException;
use App\Http\Requests\ProductRequest;
use App\Models\{Product, ProductType, Supplier, Type, Subtype};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{DB, Log};

class ProductService
{   
    /**
     * Request to create a product
     * @param ProductRequest $request
     * @return array
     */
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
            throw new InternalException($e->getMessage(), StatusCode::ERROR->value, $e);
            return ['product' => $products, 'message' => 'Failed to add product'];
        }
    }

    /**
     * Request to update a product
     * @param ProductRequest $request
     * @param Product $product
     * @return array
     */
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
            Log::error("Product update failed - ID: {$product->id}", ['error' => $e->getMessage(), 'request' => $request->validated()]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['product' => $products, 'message' => 'Failed to update product'];
        }
    }

    /**
     * Request to delete a product
     * @param Product $product
     * @return array
     */
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

    /** 
     * Create a new product and supplier
     * @param ProductRequest $request
     * @return array
     */
    private function createProduct(ProductRequest $request): array
    {   
        $validated = $request->safe();

        // new supplier data
        $supplier = $this->handleSupplier($validated->only(['supplier_name', 'company_name', 'address', 'contact']));

        // new product data with supplier id
        $product = $this->handleProduct($validated->only(['product_name', 'original_price', 'description']), [
            'supplier_id' => $supplier->id,
        ]);

        // pass supplier id and product name to checkIfExists
        // $product = $this->checkIfExists([
        //     'product_name' => $validated->product_name,
        //     'supplier_id' => $supplier->id,
        // ], $validated);

        // categorize product types
        // if product type already exists, it will not create a new one
        $productType = $this->handleProductType($validated->only(['type', 'subtype']), [
            'product_id' => $product->id,
        ]);

        return compact('supplier', 'productType', 'product');
    }
    
    /**
     * Handle supplier creation
     * @param array $supplierData
     * @return Supplier
     */
    private function handleSupplier(array $supplierData): Supplier
    {
        // creates new supplier if not exists
        return Supplier::firstOrCreate([
            'supplier_name' => $supplierData['supplier_name'],
            'company_name' => $supplierData['company_name'],
            'address' => $supplierData['address'],
            'contact' => $supplierData['contact'],
        ]);
    }

    /**
     * Handle product creation
     * @param array $productData
     * @param array $relations
     * @return Product
     */
    private function handleProduct(array $productData, array $relations): Product
    {
        // creates new product if not exists
        return Product::create([
            'product_name' => $productData['product_name'] ?? 'No Name',
            'supplier_id' => $relations['supplier_id'],
            'original_price' => $productData['original_price'],
            'description' => $productData['description'],
        ]);
    }

    /**
     * Handle product types
     * @param array $typeData
     * @param array $relations
     * @return ProductType|Collection
     */
    private function handleProductType(array $typeData, array $relations): ProductType|Collection
    {   
        // create new product main type
        $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);
        // checks if subtype is an array or a single value
        $subtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];

        $productTypes = collect();
        foreach ($subtypes as $subtypeName) {
            $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);
            $productTypes->push(
                ProductType::firstOrCreate([
                    'type_id' => $mainType->id ?? null,
                    'subtype_id' => $subType->id ?? null,
                    'product_id' => $relations['product_id'],
                ]),
            );
        }
        return $productTypes->count() === 1 ? $productTypes->first() : $productTypes;
    }

    /**
     * Update an existing product
     * @param ProductRequest $request
     * @param Product $product
     * @return array
     */
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

    /**
     * Update product details
     * @param Product $product
     * @param array $productData
     * @param array $relations
     * @return Product
     */
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

    /**
     * Update or keep existing supplier
     * @param Supplier|null $supplier
     * @param array $supplierData
     * @return Supplier
     */
    private function updateOrKeepSupplier(?Supplier $supplier, array $supplierData): Supplier
    {
        if (!$supplier || $this->supplierDataChanged($supplier, $supplierData)) {
            return $this->handleSupplier($supplierData);
        }

        return $supplier;
    }

    /**
     * Check if supplier data has changed
     * @param Supplier $supplier
     * @param array $supplierData
     * @return bool
     */
    private function supplierDataChanged(Supplier $supplier, array $supplierData): bool
    {
        return $supplier->supplier_name !== $supplierData['supplier_name'] || $supplier->company_name !== $supplierData['company_name'] || $supplier->address !== $supplierData['address'] || $supplier->contact !== $supplierData['contact'];
    }

    /**
     * Update or keep existing product types
     * @param Product $product
     * @param array $typeData
     * @return array
     */
    private function updateOrKeepProductTypes(Product $product, array $typeData): array
    {
        $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);
        $subtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];

        // Get existing product types for this product
        $existingTypes = $product->productTypes->with(['type', 'subtype'])->get();

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
            $productTypes = ProductType::firstOrCreate([
                'type_id' => $mainType->id,
                'subtype_id' => $subType->id,
                'product_id' => $product->id,
            ]);

            $newProductTypes->push($productTypes);
        }

        // Return all current product types (kept + new)
        return $product->productType
            ->whereIn('subtype_id', Subtype::whereIn('subtype_name', $subtypes)->pluck('id'))
            ->get()
            ->toArray();
    }

    /**
     * Check if product type data has changed
     * @param Product $product
     * @param array $typeData
     * @return bool
     */
    private function typeDataChanged(Product $product, array $typeData): bool
    {
        $currentMainType = $product->productTypes->first()->type->type_name ?? null;
        dd($product->productTypes->subtype_id);
        $currentSubtypes = $product->productTypes
            ->with('subtype')
            ->get()
            ->map(function ($productTypes) {
                return $productTypes->subtype->subtype_name ?? null;
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

     /**
     * Check if the product already exists in the database
     * @param array $data
     * @return void
     * @throws \RuntimeException
     */
    private function checkIfExists($data, $validated)
    {
        $product = Product::where('product_name', $data['product_name'])
        ->where('supplier_id', $data['supplier']->id)->first();

        if ($product->exists())
        {   
            Log::alert('Product already exists', ['product' => $product->product_name, 'supplier' => $data['supplier']->supplier_name]);
            return [
                'product' => $product,
                'message' => 'Product already exists',
            ];
            throw new \RuntimeException('Product already exists');
        }

        // $product = $this->handleProduct($validated->only(['product_name', 'original_price', 'description']), [
        //     'supplier_id' => $data['supplier']->id,
        // ]);

        // return $product;
    }

    /**
     * Validate update results
     * @param array $updatedData
     * @return void
     * @throws \RuntimeException
     */
    protected function validateUpdateResults(array $updatedData): void
    {
        foreach ($updatedData as $field => $success) {
            if (!$success) {
                throw new \RuntimeException("Failed to update {$field}");
            }
        }
    }
}
