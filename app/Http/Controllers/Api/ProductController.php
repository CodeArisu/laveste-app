<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends ApiBaseController
{   
    public function __construct(protected ProductService $productService) {}

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function store(ProductRequest $request) : JsonResponse
    {   
        $createdProduct = $this->productService->requestCreateProduct($request);
        return $this->sendCreateResponse($createdProduct['message'], $createdProduct['product']);
    }

    public function update(ProductRequest $request, Product $product) : JsonResponse
    {   
        $updatedProduct = $this->productService->requestUpdateProduct($request, $product);
        return $this->sendUpdateResponse($updatedProduct['message'], $updatedProduct['product'], $updatedProduct['updated_fields']);
    }

    public function destroy(Product $product)
    {
        $deletedProduct = $this->productService->requestDeleteProduct($product);
        return $this->sendDeleteResponse($deletedProduct['message'], $deletedProduct['deleted'], $deletedProduct['product_name']);
    }
}
