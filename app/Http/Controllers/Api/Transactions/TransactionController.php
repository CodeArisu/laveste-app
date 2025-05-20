<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Enum\PaymentMethods;
use App\Events\TransactionSession;
use App\Http\Requests\TransactionRequest;
use App\Models\Catalog;
use App\Models\Transactions\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionController
{
    private const PROCESS_STEP = 2;
    public function __construct(protected TransactionService $transactionService) {}

    // form
    public function index(Catalog $catalogs)
    {
        if (!Session::has('checkout.customer_data')) {
            return redirect()->route('cashier.details')->with('error', 'Please fill out the customer details first.');
        }

        $customerData = $this->transactionService->getCustomerData();

        $formattedDates = $this->transactionService->getFormattedDates($customerData);

        $totalPrice = $this->transactionService->getTotalPrice($catalogs->garment->rent_price);

        return view('src.cashier.checkout2', [
            'catalog' => $catalogs,
            'totalPrice' => $totalPrice,
            'customerData' => $customerData,
            'formattedDates' => $formattedDates,
            'paymentMethods' => PaymentMethods::cases()
        ]);
    }

    public function show(Transaction $transaction)
    {
        $originalPrice = $transaction->productRent->catalog->garment->rent_price;
        $payment = $transaction->payment;

        $customerData = $this->transactionService->getFilteredDatesData($transaction->productRent->customerRent);
        $formattedDates = $this->transactionService->getFormattedDates($customerData);

        $totalPrice = $this->transactionService->getTotalPrice($originalPrice);

        $totalChange = $this->transactionService->getTotalChange($totalPrice, $payment);

        return view('src.cashier.receipt', [
            'transactions' => $transaction,
            'formattedDates' => $formattedDates,
            'originalPrice' => $originalPrice,
            'totalPrice' => $totalPrice,
            'totalChange' => $totalChange,
        ]);
    }

    public function store(TransactionRequest $request)
    {
        $transaction = $this->transactionService->requestTransaction($request);
        return redirect()->route($transaction['route'], ['transaction' => $transaction['transactionData']])->with('success', $transaction['message']);
    }

    public function verifyCode(TransactionRequest $request)
    {
        $validated = $request->safe();
        $data = $validated->only('payment', 'coupon_code');

        $verified = $this->transactionService->execVerifyCode($data['coupon_code']);

        if (empty($verified['has_discount']) || !isset($verified['has_discount'])) {
            return redirect()->back()->with('failed', 'Code doesnt exists!');
        }

        $converted = ($verified['discount_amount'] * 100);

        return redirect()->back()->with([
            'success' => 'Currently using ' . $verified['coupon_type'] . ' Code verified!',
            'discount' => "-" . $converted . "%",
            'payment' => $data['payment'],
            'coupon_code' => $data['coupon_code'],
        ]);
    }
}
