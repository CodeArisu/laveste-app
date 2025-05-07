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

    public function completeCheckout($request)
    {   
        try {
            return DB::transaction(function () use ($request) {
                $request->merge([
                    'session_data' => [
                        'customer_data' => $this->transactionService->getCustomerData(),
                        'transaction_data' => $this->transactionService->getTransactionData(),
                    ],
                ]);

                $customerData = $request->session_data['customer_data'];
                $transactionData = $request->session_data['transaction_data'];

                $catalogData = Catalog::where('id', $request['catalogs'])->first();
                
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
