<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Enum\PaymentMethods;
use App\Events\TransactionSession;
use App\Http\Requests\TransactionRequest;
use App\Models\Catalog;
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

    public function show(Catalog $catalogs)
    {   
        $customerData =  $this->transactionService->getCustomerData();

        $transactionData = $this->transactionService->getTransactionSessionData();
    
        $formattedDates = $this->transactionService->getFormattedDates($customerData);
        
        return response()->json([
            'catalog' => $catalogs,
            'customer_data' => $customerData,
            'date_format' => $formattedDates,
            'transaction_data' => $transactionData,
            'process_step' => self::PROCESS_STEP,
        ]);
    }

    public function store(TransactionRequest $request)
    {   
        $this->transactionService->requestTransaction($request);
        return redirect()->route('cashier.receipt')->with('success', 'Transaction created successfully.');
    }
}
