<?php

namespace App\Services\Products;

use App\DTOs\ProductDTO;
use App\Exceptions\ProductException;
use App\Http\Requests\ProductRequest;
use App\Models\Products\{Product, ProductCategories, Supplier, Type, Subtype};
use App\Repositories\BaseRepository;
use App\Traits\ValidationControl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductService extends BaseRepository
{
    use ValidationControl;

    public function __construct(
        protected SupplierServices $supplierServices,
        protected CategoryServices $categoryServices
    ) {
        $this->model = new Product();
    }

    /**
     * Request to create a product
     * @param ProductRequest $request
     * @return array
     */
    public function requestCreateProduct(ProductRequest $request): array
    {
        try {
            return DB::transaction(function () use ($request) {
                // sets DTO data from request
                $data = $this->processData($request);
                // handles data queries
                $products = $this->createProduct($data);

                // product data flag
                if (!$products) {
                    throw ProductException::productNotFound();
                }

                return [
                    'message' => "{$products['product_name']} has been added",
                ];
            });
        } catch (ValidationException $e) {
            throw ProductException::productValidationFailed();
        } catch (ModelNotFoundException | QueryException $e) {

            dd($e->getMessage());
            report($e);
            throw ProductException::productNotFound();
        } catch (\RuntimeException | \Exception $e) {
            dd($e->getMessage());
            report($e);
            throw ProductException::productCreateFailed();
        }
    }

    /**
     * Create a new product and supplier
     * @param ProductRequest $request
     * @return array
     */
    private function createProduct(array $data)
    {
        // new supplier data
        $supplier = $this->supplierServices->create($data['supplierData']);

        if (!$supplier) {
            throw ProductException::productCreateFailed();
        }

        $product = $this->create(
            array_merge(
                $data['productData'],
                ['supplier_id' => $supplier->id]
            )
        );

        // categorize product types
        // if product type already exists, it will not create a new one
        $this->handleProductCategory(
            array_merge(
                $data['categoricalData'],
                ['product_id' => $product->id]
            )
        );

        return $product;
    }

    /**
     * Handle product types
     * @param array $typeData
     * @param array $relations
     * @return ProductType|Collection
     */
    private function handleProductCategory(array $data): ProductCategories|Collection
    {
        // create new product main type
        $mainType = Type::firstOrCreate(['type_name' => $data['type']]);
        // checks if subtype is an array or a single value
        $subtypes = is_array($data['subtype']) ? $data['subtype'] : [$data['subtype']];

        // collect all product types
        $productTypes = collect();
        foreach ($subtypes as $subtypeName) {
            $subType = Subtype::firstOrCreate(['subtype_name' => $subtypeName]);
            $productTypes->push(
                $this->categoryServices->create([
                    'type_id' => $mainType->id,
                    'subtype_id' => $subType->id,
                    'product_id' => $data['product_id'],
                ])
            );
        }
        return $productTypes->count() === 1 ? $productTypes->first() : $productTypes;
    }

    private function processData($request)
    {
        // data validated then passed to the DTO
        $validated = $request->validated();
        $this->setDto(new ProductDTO($validated));

        // checks the validated data from repository and
        // gets data as array
        return $this->validatedDataRepository(
            $validated,
            fn() => throw ProductException::productValidationFailed(),
        );
    }
}
