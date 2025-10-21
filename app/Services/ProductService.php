<?php

namespace App\Services;


use App\Models\Products\Product;
use App\Exceptions\ProductException;
use App\Http\Requests\ProductRequest;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductService extends ProductRepository
{
    public function __construct(
        protected Product $product,
    )
    {
        parent::__construct($product);
    }

    public function requestProductIndex()
    {
        //
    }

    /**
     * Request to create a product
     * @param ProductRequest $request
     * @return array
     */
    public function requestCreateProduct($request)
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
    public function requestUpdateProduct($request, Product $product)
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
            $deleted = $this->deleteProduct($product);
            if (!$deleted) {
                throw ProductException::productDeleteFailed();
            }
            return ['message' => 'Successfully deleted', 'route' => 'dashboard.product.index'];
        } catch (\Exception $e) {
            report($e);
            throw ProductException::productDeleteFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw ProductException::productNotFound();
        }
    }
}
