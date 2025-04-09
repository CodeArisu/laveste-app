<?php

namespace App\Services;

use App\Enum\PaymentMethods;
use App\Models\Transactions\PaymentMethod;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;

class TransactionService
{
    public function __construct(protected ProductRentService $productRentService){}

    /**
     * Request transaction creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array transaction data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    private function createTransaction($request)
    {   
        if (!PaymentMethod::exists()) {
            $this->generatePaymentMethods();
        }

        $validated = $request->safe();
        $productId = $this->getProductRentId($request, $validated->get('customer_rented_id'));

        $transaction = $this->handleTransaction(
            $validated->only([
                'total_amount',
                'has_discount',
                'discount_amount',
                'vat',
            ]),
            [   
                'customer_rented_id' => $productId,
                'payment_method_id' => $validated->get('payment_method_id') ?? 1,
            ]
        );

        return compact('transaction');
    }

    /**
     * Request transaction creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array transaction data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    private function handleTransaction(array $data, $relations)
    {
        return Transaction::create([
            'customer_rented_id' => $relations['customer_rented_id'],
            'total_amount' => $data['total_amount'],
            'has_discount' => $data['has_discount'],
            'discount_amount' => $data['discount_amount'],
            'vat' => $data['vat'],
            'payment_method_id' => $relations['payment_method_id']
        ]);
    }

    /**
     * Request product rent creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array product rent data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    private function getProductRentId($request, $customerRentedId)
    {   
        if (empty($customerRentedId)) {
            throw new \RuntimeException('No product were rented');
        }

        $productFound = ProductRent::where('customer_rented_id', $customerRentedId)
        ->firstOrFail();

        if (empty($productFound)) {
            throw new \RuntimeException('No rented product found');
            $productFound = $this->productRentService->execProductRent($request);
            return $productFound['productRent']->id;
        }

        return $productFound['productRent']->id ?? null;
    }

    /**
     * Generate payment methods.
     *
     * @return void
     */
    private function generatePaymentMethods() : void
    {
        $existingMethods = array_map('strtolower', PaymentMethod::pluck('method_name')->toArray());
        $allMethod = array_map(fn($status) => strtolower($status->label()), PaymentMethods::cases());

        if (count(array_diff($allMethod, $existingMethods))) {
            foreach (PaymentMethods::cases() as $status) {
                PaymentMethod::updateOrCreate(['method_name' => $status->label()]);
            }
        }
    }
}
