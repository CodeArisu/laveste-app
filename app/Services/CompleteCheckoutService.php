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

                // check discount_code if exists
                $couponExists =  Discounts::Regular->type();
                
                // checks if there is a coupon or discount applied
                $isDiscounted = $this->checkIfHasDiscount([
                    'has_discount' => true,
                    'coupon_type' => $couponExists ?? null,
                ]);

                dd($isDiscounted);
                
                // records customer data
                $productRent = $this->productRentService->execProductRent($customerData, $catalogData);
                // records transaction data
                $transaction = $this->transactionService->execTransaction($transactionData, $productRent);
                // product status to "Rented"
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

    private function checkIfHasDiscount(array $discount)
    {
        if (!$discount['has_discount']) {
            return false;
        }

        $discountPercent = $this->discountType($discount['coupon_type']);

        return [
            'has_discount' => $discount['has_discount'],
            'coupon_type' => $discount['coupon_type'],
            'discount_amount' => $discountPercent,
        ];
    }

    private function discountType($discountType)
    {   
        $discountAmount = 0;

        switch($discountType) {
            case Discounts::Regular->type():
                $discountAmount = Discounts::Regular->percent();
                break;
            case Discounts::Promo->type():
                $discountAmount = Discounts::Promo->percent();
                break;
            case Discounts::Limited->type():
                $discountAmount = Discounts::Limited->percent();
                break;
            case Discounts::Senior->type():
                $discountAmount = Discounts::Senior->percent();
                break;
            default:
                $discountAmount = 0;
                break;
        }

        return $discountAmount;
    }

    private function checkIfCodeExists($code)
    {
        return $code;
    }
}
