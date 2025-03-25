<?php

namespace App\Http\Controllers\api;

use App\Enum\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

use App\Models\Product;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

abstract class ApiBaseController extends Controller
{    
    abstract function store(ProductRequest $request, ProductService $productService);
    abstract function update(ProductRequest $request, Product $product);

    protected function isChecked($object, $message) : void
    {
        if ($object === false || $object === null) {
            abort(StatusCode::ERROR->value, $message);
        }
    }

    protected function sendError($message) : JsonResponse
    {
        return response()->json([
            'error' => $message,
        ], StatusCode::ERROR->value);
    }

    protected function sendSuccess($message) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => $message,
        ], StatusCode::SUCCESS->value);
    }
}