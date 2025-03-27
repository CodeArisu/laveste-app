<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends ApiBaseController
{   
    public function __construct(    
        protected ProductService $productService
    ) {}

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function store(ProductRequest $request, ProductService $productService) : JsonResponse
    {   
        try {
            return DB::transaction(function () use ($request, $productService) {
                $products = $productService->createProduct($request);

                array_walk($products, fn($prod) => $this->isChecked($prod, 'Failed to create product!'));

                return $this->sendSuccess('Created Successfully!');
            });
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }

    public function update(ProductRequest $request, Product $product) 
    {
        try {
            return DB::transaction(function () use ($request, $product) {
                $result = $this->productService->updateProduct($request, $product);
                
                collect($result)->each(fn($item, $key) => 
                    $this->isChecked($item, ucfirst($key).' update failed')
                );
        
                return $this->sendSuccess('Product successfully updated!');
            });
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return $this->sendSuccess('Deleted Successfully!');
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }
}
