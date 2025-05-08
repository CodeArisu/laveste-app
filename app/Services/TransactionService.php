<?php

namespace App\Services;

use App\Enum\PaymentMethods;
use App\Http\Requests\TransactionRequest;
use App\Models\Transactions\PaymentMethod;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use Illuminate\Support\Facades\Session;

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
    public function requestTransaction(TransactionRequest $request)
    {   
        $validated = $request->validated();

        if (!Session::has('checkout.customer_data')) {
            throw new \RuntimeException('Session does not exist');
        }

        Session::put('checkout.transaction_data', $validated);
    }

    public function getTotalPrice($price)
    {   
        $total = ($price + ($price * .25));
        return $total;
    }

    public function getCustomerData()
    {   
        $customerData = Session::get('checkout.customer_data');
        return $customerData;
    }

    public function getTransactionData()
    {   
        $transactionData = Session::get('checkout.transaction_data');
        return $transactionData;
    }

    private function convertDateFormat($date)
    {
        $date = new \DateTime($date);
        return $date->format('F j, Y'); // "February 5, 2024"
    }

    public function getFormattedDates(array $data) : array
    {   
        $dataFields = ['pickup_date', 'return_date', 'rented_date'];
        $formattedDates = [];

        foreach($dataFields as $date) {
            if (!empty($data[$date])) {
                $formattedDates[$date] = $this->convertDateFormat($data[$date]);
            }
        }

        return $formattedDates;
    }

    /**
     * Request transaction creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array transaction data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    private function createTransaction($transactionData, $productRent)
    {   

        dd($transactionData); 
        
        $productId = $this->getProductRentId($transactionData, $productRent);

        $transaction = $this->handleTransaction(
            $transactionData, [   
                'customer_rented_id' => $productId,
        ]);

        return $transaction;
    }

    public function execTransaction($transactionData, $productRent)
    {   
        return $this->createTransaction($transactionData, $productRent);
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
            'product_rented_id' => $relations['product_rented_id'] ?? null,
            'total_amount' => $data['total_amount'] ?? 0,
            'has_discount' => $data['has_discount'] ?? '0',
            'discount_amount' => $data['discount_amount'] ?? 0,
            'vat' => $data['vat'] ?? .12,
            'payment_method_id' => $data['payment_method_id'] ?? PaymentMethods::CASH->value,
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

        // finds if the product is already rented
        $productFound = ProductRent::where('customer_rented_id', $customerRentedId)->firstOrFail();

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

    private function checkForPaymentMethod($paymentMethod)
    {
        $paymentMethod = PaymentMethod::where('method_name', $paymentMethod)->first();
        if (empty($paymentMethod)) {
            throw new \RuntimeException('Payment method not found');
        }

        return $paymentMethod;
    }
}
