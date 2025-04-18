<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\{Requests\ProductRequest, Resources\ProductResource};
use App\Models\Products\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;   

class ProductController extends ApiBaseController
{   
    public function __construct(protected ProductService $productService) {}

    public function index()
    {
        //
    }

    public function store(ProductRequest $request) : JsonResponse
    {   
        $createdProduct = $this->productService->requestCreateProduct($request);
        return $this->sendResponse($createdProduct['data'], $createdProduct['message']);
    }

    public function show(Product $product)
    {   
        // Eager load the relationships to avoid N+1 query problem
        $products = $product->with(['subtypes', 'types', 'supplier'])->find($product);
        return ProductResource::collection($products);
    }

    public function update(ProductRequest $request, Product $product) : JsonResponse
    {   
        $updatedProduct = $this->productService->requestUpdateProduct($request, $product);
        return $this->sendResponse($updatedProduct['data'], $updatedProduct['message']);
    }

    public function destroy(Product $product)
    {
        $deletedProduct = $this->productService->requestDeleteProduct($product);
        return $this->sendResponse($deletedProduct['data'], $deletedProduct['message']);
    }
}
