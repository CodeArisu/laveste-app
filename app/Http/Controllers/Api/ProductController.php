<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
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
        return $this->sendResponse($createdProduct['message'], $createdProduct['product']);
    }

    public function show(Product $product)
    {   
        $products = $product->with(['subtypes', 'supplier'])->find($product);
        return ProductResource::collection($products);
    }

    public function update(ProductRequest $request, Product $product) : JsonResponse
    {   
        $updatedProduct = $this->productService->requestUpdateProduct($request, $product);
        return $this->sendResponse($updatedProduct['message'], $updatedProduct['product']);
    }

    public function destroy(Product $product)
    {
        $deletedProduct = $this->productService->requestDeleteProduct($product);
        return $this->sendResponse($deletedProduct['message'], $deletedProduct['product']);
    }
}
