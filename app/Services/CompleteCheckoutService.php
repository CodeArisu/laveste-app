<?php

namespace App\Services;

use App\Events\CatalogStatus;
use App\Models\Catalog;
use App\Models\Statuses\ProductRentedStatus;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CompleteCheckoutService
{
    public function __construct(
        protected ProductRentService $productRentService, 
        protected TransactionService $transactionService
    ){}

    public function completeCheckout($transactionData)
    {   
        try {
            $result = DB::transaction(function () use ($transactionData) {
                $transactionData = array_merge([
                    'customer_data' => $this->transactionService->getCustomerData(), 
                    'transaction_data' => $transactionData
                ]);

                $customerData = $transactionData['customer_data'];
                $transactionData = $transactionData['transaction_data'];

                $catalogData = Catalog::where('id', $transactionData['catalog_id'])->first();
                
                $productRent = $this->productRentService->execProductRent($customerData, $catalogData);
                $transaction = $this->transactionService->execTransaction($transactionData, $productRent);

                $this->handleStatusChange($productRent, $catalogData);

                return [
                    'product_rent' => $productRent,
                    'transaction' => $transaction,
                ];
            });

            // removes previous session data
            session()->forget('checkout.customer_data', 'checkout.transaction_data');

            return $result;
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
            return response()->json([
                'error' => 'Transaction failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function handleStatusChange($productRent, $catalogData)
    {
        // event to change or update global status
        if (!$productRent['product_rented_status_id']) throw new RuntimeException('status does not exists.');

        $rentStatus = ProductRentedStatus::where('id', $productRent['product_rented_status_id'])->first();

        event(new CatalogStatus($rentStatus, $catalogData->id));
    }

}
