<?php

namespace App\Services;

use App\Enum\Discounts;
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
    ) {}

    public function completeCheckout($transactionData)
    {
        try {
            $result = DB::transaction(function () use ($transactionData) {
                $transactionData = array_merge([
                    'customer_data' => $this->transactionService->getCustomerData(),
                    'transaction_data' => $transactionData,
                ]);

                $customerData = $transactionData['customer_data'];
                $transactionData = $transactionData['transaction_data'];

                $catalogData = Catalog::where('id', $transactionData['catalog_id'])->first();

                $transactionData = $this->checkIfRegular($customerData['is_regular'], $transactionData);
                
                // records customer data
                $productRent = $this->productRentService->execProductRent($customerData, $catalogData);
                // records transaction data
                $transaction = $this->transactionService->execTransaction($transactionData, $productRent);

                $additionalData = $this->filterAdditionalData($transactionData);

                // product status to "Rented"
                $this->handleStatusChange($productRent, $catalogData);

                return [
                    'product_rent' => $productRent,
                    'transaction' => $transaction,
                    'additional_data' => $additionalData,
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

    private function checkIfRegular($isRegular, array $transactionData)
    {
        if (!$isRegular) {
            return $transactionData;
        }

        $discount = Discounts::Regular->percent();

        $newTotalDiscount =  ($transactionData['discount_amount'] + $discount);

        $newTotalAmount = $this->transactionService->execGetDiscountedAmount($transactionData['total_amount'], $discount);

        $newTotalChange = $this->transactionService->getTotalChange($newTotalAmount, $transactionData['payment']);

        $transactionData['total_amount'] = $newTotalAmount;
        $transactionData['discount_amount'] = $newTotalDiscount;
        $transactionData['change'] = $newTotalChange;

        return $transactionData;
    }

    private function filterAdditionalData(array $transactionData)
    {
        return [
            'discount_percent' => $transactionData['discount_amount'],
            'change' => $transactionData['change'],
            'code' => $transactionData['code']
        ];
    }
}
