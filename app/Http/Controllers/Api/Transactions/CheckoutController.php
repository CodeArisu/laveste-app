<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Models\Catalog;
use App\Services\CompleteCheckoutService;
use Illuminate\Http\Request;

class CheckoutController
{
    public function __construct(
        protected CompleteCheckoutService $completeCheckoutService, 
    ) {}

    public function store(Request $request)
    {
        $this->completeCheckoutService->completeCheckout($request);
    }
}
