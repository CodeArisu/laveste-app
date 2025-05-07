<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CompleteCheckoutService
{
    public function __construct(
        protected ProductRentService $productRentService, 
        protected TransactionService $transactionService
    ){}

    public function completeCheckout($request)
    {   
        try {
            return DB::transaction(function () use ($request) {
                $productRent = $this->productRentService->execProductRent($request);
                $transaction = $this->transactionService->execTransaction($request);
                
                return [
                    'product_rent' => $productRent,
                    'transaction' => $transaction,
                ];
            });
        } catch (\Exception $e) {
            // Handle exception
            return response()->json(['error' => 'An error occurred'], 500);
        }
       
    }
}
