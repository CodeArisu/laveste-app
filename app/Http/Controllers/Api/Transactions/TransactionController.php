<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use App\Enum\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransactionController
{   
    public function __construct(protected TransactionService $transactionService) {}

    // form
    public function index()
    {   
        return view('src.cashier.checkout2', [
            'paymentMethods' => PaymentMethods::cases()
        ]);
    }

    // public function show()
    // {   
    //     $transactionData = Session::get('checkout.transaction_data');

    //     return response()->json([
    //         'transaction_data' => $transactionData,
    //         'process_step' => 2,
    //     ]);
    // }

    public function store(TransactionRequest $request)
    {
        $transaction = $this->transactionService->requestTransaction($request);
        if(!Session::has('checkout.transaction_data')) {
            // return exception
            dd('Session does not exist');
        }

        return redirect()->route('cashier.checkout.show');
    }
}
