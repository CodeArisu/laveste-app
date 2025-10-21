<?php

namespace App\Services;

use App\Enum\Discounts;
use App\Enum\PaymentMethods;
use App\Enum\ProductStatus;
use App\Enum\RentStatus;
use App\Events\TransactionSession;
use App\Http\Requests\TransactionRequest;
use App\Models\Catalog;
use App\Models\Statuses\ProductRentedStatus;
use App\Models\Transactions\PaymentMethod;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use Illuminate\Support\Facades\Session;

class TransactionService
{
    public function __construct(protected ProductRentService $productRentService) {}

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

            $response = $this->setTransactionData($catalogId);
            return [
                'message' => 'Transaction Created Successfully.',
                'route' => 'cashier.checkout.receipt',
                'transactionData' => $response['transaction'],
                'additionalData' => $response['additional_data'],
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function setTransactionData($catalogId)
    {
        $sessionName = 'checkout.transaction_data';

        if (!Session::has($sessionName)) {
            throw new \RuntimeException('Session does not exist');
        }

        $transactionSession = Session::get($sessionName);

        $event = new TransactionSession($transactionSession, $catalogId);

        event($event);
        // triggers transaction event
        \Log::info('Event Triggered');

        return $event->response;
    }

    public function checkIfPaymentExceeds($payment, $totalPayment)
    {
        if ($payment < $totalPayment) {
            throw new \Exception('Payment must be greater than total.');
        }

        return $payment;
    }

    public function getTotalPrice($price)
    {
        // calculates price + 12% vat
        $total = ($price + ($price * .12));
        return $total;
    }

    public function getTotalChange($totalPayment, $payment)
    {
        $change = ($payment - $totalPayment);
        return $change;
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

    public function execGetDiscountedAmount($price, float $discount)
    {
        return $this->getDiscountedAmount($price, $discount);
    }

    private function getDiscountedAmount($price, float $discount)
    {
        // price * 12%
        $total = ($price - ($price * $discount));
        return $total;
    }

    public function getCheckoutData(array $transactionData, $catalogId)
    {
        $payment = $transactionData['payment'];
        $couponCode = $transactionData['coupon_code'];

        $catalog = Catalog::where('id', $catalogId)->first();
        $price = $catalog->garment->rent_price;

        $totalPayment = $this->totalPayments($couponCode, $payment, $price);

        return [
            'payment' => $transactionData['payment'],
            'total_amount' => $totalPayment['total_amount'],
            'change' => $totalPayment['total_change'],
            'coupon_code' => $transactionData['coupon_code'] ?? null,
            'discount_amount' => $totalPayment['discount_data']['discount_amount'] ?? 0,
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

    private function convertDates(array $data): array
    {
        $dataFields = ['pickup_date', 'return_date', 'rented_date'];
        $formattedDates = [];

        foreach ($dataFields as $date) {
            if (!empty($data[$date])) {
                $formattedDates[$date] = $this->convertDateFormat($data[$date]);
            }
        }
        return $formattedDates;
    }

    public function getFormattedDates(array $data): array
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

        if (!$productRent) {
            throw new \RuntimeException('No product rent data');
        }

        $transaction = $this->handleTransaction($transactionData, [
            'product_rented_id' => $productRent->id,
        ]);

        return $transaction;
    }

    public function execTransaction($transactionData, $productRent)
    {
        return $this->createTransaction($transactionData, $productRent);
    }

    public function checkIfDiscountCompatible() {}

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
    private function generatePaymentMethods(): void
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

    private function checkIfHasDiscount(array $discount)
    {
        if (!$discount['has_discount']) {
            return null;
        }

        $discountPercent = $this->discountType($discount['coupon_type']);

        return [
            'has_discount' => $discount['has_discount'],
            'coupon_type' => $discount['coupon_type'],
            'discount_amount' => $discountPercent,
        ];
    }

    public function execVerifyCode($coupon)
    {
        $couponExists = $this->verifyCode($coupon);

        $isDiscounted = $this->checkIfHasDiscount([
            'has_discount' => $couponExists['has_discount'],
            'coupon_type' => $couponExists['type'] ?? null,
        ]);

        return $isDiscounted;
    }

    private function verifyCode($coupon)
    {
        $coupon = \App\Models\Discount::where('code', $coupon)->first();

        if (!$coupon) {
            \Log::error('Coupon doesn\'t exists.');
            return ['has_discount' => false, 'type' => $coupon->coupon_type ?? null];
        }

        if ($coupon->expiry_date && now()->gt($coupon->expiry_date)) {
            \Log::info('Coupon Expired');
            return ['has_discount' => false, 'type' => $coupon->coupon_type ?? null];
        }

        return ['has_discount' => true, 'type' => $coupon->coupon_type];
    }

    private function discountType($discountType)
    {
        $discountAmount = 0;

        switch ($discountType) {
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

    private function totalPayments($couponCode, $payment, $price)
    {
        // check discount_code if exists
        $couponExists = $this->verifyCode($couponCode);

        // checks if there is a coupon or discount applied
        $isDiscounted = $this->checkIfHasDiscount([
            'has_discount' => $couponExists['has_discount'],
            'coupon_type' => $couponExists['type'] ?? null,
        ]);

        // total price * 12% adds to the total payment
        $totalPayment = $this->getTotalPrice($price);

        if ($isDiscounted) {
            // lessen the total price amount
            $totalPayment = $this->getDiscountedAmount($totalPayment, $isDiscounted['discount_amount']);
        }

        // total payment - price
        $totalChange = $this->getTotalChange($totalPayment, $payment);

        $this->checkIfPaymentExceeds($payment, $totalPayment);

        return [
            'total_change' => $totalChange,
            'discount_data' => $isDiscounted,
            'total_amount' => $totalPayment,
        ];
    }
}
