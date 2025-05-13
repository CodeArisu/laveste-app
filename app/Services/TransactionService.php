<?php

namespace App\Services;

use App\Enum\PaymentMethods;
use App\Enum\ProductStatus;
use App\Enum\RentStatus;
use App\Events\TransactionSession;
use App\Http\Requests\TransactionRequest;
use App\Models\Statuses\ProductRentedStatus;
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
        try {
            $validated = $request->validated();

            if (!Session::has('checkout.customer_data')) {
                throw new \RuntimeException('Session does not exist');
            }
    
            Session::put('checkout.transaction_data', $validated);
            $catalogId = $request->only(['catalog']);
    
            $this->setTransactionData($catalogId);

            return [
                'message' => 'Transaction Created Successfully.',
                'url' => 'cashier.checkout.receipt',
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function setTransactionData($catalogId)
    {   
        $sessionName = 'checkout.transaction_data';

        if(!Session::has($sessionName)) {
            throw new \RuntimeException('Session does not exist');
        }

        $transactionSession = Session::get($sessionName);

        // triggers transaction event
        event(new TransactionSession($transactionSession, $catalogId));
        \Log::info('Event Triggered');
    }

    public function getTotalPrice($price)
    {   
        // calculates price + 12% vat
        $total = ($price + ($price * .12));
        return $total;
    }

    public function getCustomerData()
    {   
        $customerData = Session::get('checkout.customer_data');
        return $customerData;
    }

    public function getTransactionSessionData()
    {   
        $transactionData = Session::get('checkout.transaction_data');
        return $transactionData;
    }

    public function getCheckoutData(array $transactionData, $catalogId)
    {   
        $totalPayment = $this->getTotalPrice($transactionData['payment']);
        return [
            'payment' => $transactionData['payment'],
            'total_amount' => $totalPayment,
            'has_discount' => $transactionData['has_discount'] ?? '0',
            'discount_amount' => $transactionData['discount_amount'] ?? 0,
            'vat' => $transactionData['vat'] ?? .12,
            'payment_method' => $transactionData['payment_method'],
            'catalog_id' => $catalogId,
        ];
    }

    private function convertDateFormat($date)
    {
        $date = new \DateTime($date);
        return $date->format('F j, Y'); // "February 5, 2024"
    }

    private function convertDates(array $data) : array
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

    public function getFormattedDates(array $data) : array
    {   
        return $this->convertDates($data);
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
        // $productId = $this->getProductRentId($transactionData, $productRent);
        if (!PaymentMethod::exists()) {
            $this->generatePaymentMethods();
        }

        if(!$productRent) {
            throw new \RuntimeException('No product rent data');
        }

        $transaction = $this->handleTransaction($transactionData, [   
            'product_rented_id' => $productRent->id,
        ]);

        return $transaction;
    }

    public function execTransaction($transactionData, $productRent)
    {   
        $this->createTransaction($transactionData, $productRent);
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
            'payment' => $data['payment'] ?? 0,
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
                PaymentMethod::updateOrCreate(['id' => $status->value, 'method_name' => $status->label()]);
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

    private function validateStatus($rentStatus)
    {   
        $rentStatus = ProductRentedStatus::where('id', $rentStatus->id)->first();

        if ($rentStatus['status_name'] !== RentStatus::RENTED->label()) {
            return null;
        }

        return ProductStatus::UNAVAILABLE->value;
    }

    public function getUpdatedStatus($rentStatus, $catalogId)
    {   
        $newStatus = $this->validateStatus($rentStatus);

        if (!$newStatus) {
            throw new \RuntimeException('Status not recognized');
        }
        
        return [
            'catalog_id' => $catalogId,
            'product_status' => $newStatus,
            'updated_at' => now(),
        ];
    }

    public function getFilteredDatesData($customerData)
    {
        return [
            'pickup_date' => $customerData['pickup_date'],
            'rented_date' => $customerData['rented_date'],
            'return_date' => $customerData['return_date'],
        ];
    }
}