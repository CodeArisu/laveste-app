<?php

namespace App\Services;

use App\Exceptions\ProductException;
use App\Http\Requests\ProductRequest;
use App\Models\Products\{Product, ProductCategories, Supplier, Type, Subtype};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductService extends BaseServicesClass
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
                    throw ProductException::productNotFound();
                }
                return ['message' => 'Successfully Created', 'route' => 'dashboard.product.form'];
            });
        } catch (\Exception $e) {
            report($e);
            throw ProductException::productCreateFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw ProductException::productNotFound();
        } catch (QueryException $e) {
            // Database query errors (constraint violations, etc.)
            report($e);
            throw ProductException::productNotFound();
        } catch (ValidationException $e) {
            // If any validation fails (though ProductRequest should handle most)
            throw ProductException::productValidationFailed();
        } catch (\RuntimeException $e) {
            // Your custom runtime exceptions
            throw ProductException::productCreateFailed();

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

                return ['message' => 'Successfully updated', 'route' => 'dashboard.product.edit'];
            });
        } catch (\Exception $e) {
            report($e);
            throw ProductException::productUpdateFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw ProductException::productNotFound();
        } catch (QueryException $e) {
            // Database query errors (constraint violations, etc.)
            report($e);
            throw ProductException::productNotFound();
        } catch (ValidationException $e) {
            // If any validation fails (though ProductRequest should handle most)
            throw ProductException::productValidationFailed();
        } catch (\RuntimeException $e) {
            // Your custom runtime exceptions
            throw ProductException::productUpdateFailed();
            dd($e->getMessage());
        }
    }

    /**
     * Request to delete a product
     * @param Product $product
     * @return array
     */
    public function requestDeleteProduct(Product $product)
    {
        try {

            $product->productCategories()->delete();

            $product->deleteOrFail();
            
            return ['message' => 'Successfully deleted', 'route' => 'dashboard.product.index'];
        } catch (\Exception $e) {
            report($e);
            throw ProductException::productDeleteFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw ProductException::productNotFound();
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

        // categorize product types
        // if product type already exists, it will not create a new one
        $this->handleProductType($validated->only(['type', 'subtype']), [
            'product_id' => $product->id,
        ]);

        return ['product' => $product];
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
    private function handleProduct(array $productData, $relations): Product
    {
        // creates new product if not exists
        return Product::firstOrCreate([
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
    private function handleProductType(array $data, $relations): ProductCategories|Collection
    {
        // create new product main type
        $mainType = Type::firstOrCreate(['type_name' => $data['type']]);
        // checks if subtype is an array or a single value
        $subtypes = is_array($data['subtype']) ? $data['subtype'] : [$data['subtype']];

        $productTypes = collect();
        foreach ($subtypes as $subtypeName) {
            $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);
            $productTypes->push(
                ProductCategories::firstOrCreate([
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
            $product->load('productCategories');

            // Get primary type (first or only one)
            $primaryProductType = $product->productCategories()->first();
        } else {
            $primaryProductType = $product->productCategories;
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
            'updated_at' => now()
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
        // Get or create the main type
        $mainType = Type::firstOrCreate(['type_name' => $typeData['type']]);

        // Normalize subtypes to array
        $subtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];

        // Get existing categories and new subtype to supply
        $existingData = $this->getExistingCategories($product, $subtypes);

        $this->handleProductCategoryUpdate($product, ['existingData' => $existingData, 'mainType' => $mainType]);

        // Update type for all remaining categories (in case main type changed)
        if ($existingData['existingCategories']->isNotEmpty()) {
            $product
                ->productCategories()
                ->whereIn('subtype_id', Subtype::whereIn('subtype_name', $subtypes)->pluck('id'))
                ->update(['type_id' => $mainType->id]);
        }

        // Return the updated product categories
        return $product
            ->productCategories()
            ->with(['type', 'subtype'])
            ->whereIn('subtype_id', Subtype::whereIn('subtype_name', $subtypes)->pluck('id'))
            ->get()
            ->toArray();
    }

    /**
     * get existing categories
     * @param Product $product, 
     * @param $subtypes
     * @return array
     */
    private function getExistingCategories($product, $subtypes)
    {
        // Get existing product categories for this product
        $existingCategories = $product
            ->productCategories()
            ->with(['type', 'subtype'])
            ->get();

        // Get all existing subtype names for this product
        $existingSubtypeNames = $existingCategories->pluck('subtype.subtype_name')->toArray();

        // Determine which subtypes need to be added
        $subtypesToAdd = array_diff($subtypes, $existingSubtypeNames);

        // Determine which subtypes need to be removed (not in the new list but exist in DB)
        $subtypesToRemove = array_diff($existingSubtypeNames, $subtypes);
        
        // Remove obsolete categories
        if (!empty($subtypesToRemove)) {
            $subtypeIdsToRemove = Subtype::whereIn('subtype_name', $subtypesToRemove)->pluck('id');
            $product->productCategories()->whereIn('subtype_id', $subtypeIdsToRemove)->delete();
        }

        return ['subtypesToAdd' => $subtypesToAdd, 'existingCategories' => $existingCategories];
    }
    
    /**
     * get existing categories
     * @param Product $product, 
     * @param mixed|array $attributes 
     */
    private function handleProductCategoryUpdate($product, array $attribute) : void
    {
        // Add new categories
        foreach ($attribute['existingData']['subtypesToAdd'] as $subtypeName) {
            $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);

            // Check if this combination already exists to avoid duplicates
            $exists = $product->productCategories()->where('type_id', $attribute['mainType']->id)->where('subtype_id', $subType->id)->exists();

            if (!$exists) {
                $product->productCategories()->create([
                    'type_id' => $attribute['mainType']->id,
                    'subtype_id' => $subType->id,
                    'updated_at' => now()
                ]);
            }

            return;
        }
    }

    /**
     * Check if product type data has changed
     * @param Product $product
     * @param array $typeData
     * @return bool
     */
    private function typeDataChanged(Product $product, array $typeData): bool
    {
        $currentMainType = $product->productCategories->first()->type->type_name ?? null;
        $currentSubtypes = $product->subtypes()->pluck('subtype_name')->filter()->unique()->values()->toArray();

        $newSubtypes = is_array($typeData['subtype']) ? $typeData['subtype'] : [$typeData['subtype']];
        sort($currentSubtypes);
        sort($newSubtypes);

        return $currentMainType !== $typeData['type'] || $currentSubtypes !== $newSubtypes;
    }
}