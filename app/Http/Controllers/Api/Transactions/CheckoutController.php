<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Services\ProductRentService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class CheckoutController
{
    public function __construct(
        protected TransactionService $transactionService, 
        protected ProductRentService $productRentService
    ) {}

    public function store(Request $request)
    {
        
    }
}
