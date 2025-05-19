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
}
