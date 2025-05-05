<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use App\Enum\PaymentMethods;
use App\Models\Catalog;
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
        $transactionData = $this->transactionService->getTransactionData();
    
        $formattedDates = $this->transactionService->getFormattedDates($customerData);
        
        return response()->json([
            'catalog' => $catalogs,
            'customer_data' => $customerData,
            'date_format' => $formattedDates,
            'transaction_data' => $transactionData,
            'process_step' => self::PROCESS_STEP,
        ]);
    }

    public function store(TransactionRequest $request, Catalog $catalogs)
    {
        $this->transactionService->requestTransaction($request);
        if(!Session::has('checkout.transaction_data')) {
            // return exception
            dd('Session does not exist');
        }

        return redirect()->route('cashier.checkout.show', ['catalogs' => $catalogs])->with('success', 'Transaction created successfully.');
    }
}
