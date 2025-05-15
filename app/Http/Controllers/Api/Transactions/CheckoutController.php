<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Models\Catalog;
use App\Models\Transactions\Transaction;
use App\Services\CompleteCheckoutService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class CheckoutController
{
    public function __construct(
        protected CompleteCheckoutService $completeCheckoutService,
        protected TransactionService $transactionService,
    ) {}

    public function show(Transaction $transaction)
    {   
        return view('src.cashier.receipt', ['transaction' => $transaction]);
    }
}
