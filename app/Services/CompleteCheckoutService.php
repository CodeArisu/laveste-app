<?php

namespace App\Services;

use App\Models\Catalog;
use Illuminate\Support\Facades\DB;

class CompleteCheckoutService
{
    public function __construct(
        protected ProductRentService $productRentService, 
        protected TransactionService $transactionService
    ){}

    public function completeCheckout($transactionData)
    {   
        try {
            return DB::transaction(function () use ($transactionData) {
                $transactionData = array_merge([
                    'customer_data' => $this->transactionService->getCustomerData(), 
                    'transaction_data' => $transactionData
                ]);

                $customerData = $transactionData['customer_data'];
                $transactionData = $transactionData['transaction_data'];

                $catalogData = Catalog::where('id', $transactionData['catalog_id'])->first();
                
                $productRent = $this->productRentService->execProductRent($customerData, $catalogData);
                $transaction = $this->transactionService->execTransaction($transactionData, $productRent);
                
                return [
                    'product_rent' => $productRent,
                    'transaction' => $transaction,
                ];
            });
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
            return response()->json([
                'error' => 'Transaction failed',
                'message' => $e->getMessage(),
            ], 500);
        }
       
    }
}
