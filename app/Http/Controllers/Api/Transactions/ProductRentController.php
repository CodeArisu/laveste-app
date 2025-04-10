<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\api\ApiBaseController;
use App\Services\ProductRentService;
use Illuminate\Http\Request;

class ProductRentController extends ApiBaseController
{
    public function __construct(protected ProductRentService $productRentService) {}

    public function store(Request $request) {
        $rentedProduct = $this->productRentService->requestProductRent($request);
        return $this->sendResponse($rentedProduct['message'], $rentedProduct['product_rent']);
    }
}
