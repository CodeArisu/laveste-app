<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Transactions\Transaction;
use App\Services\CompleteCheckoutService;
use Illuminate\Http\Request;

class CheckoutController
{
    public function __construct(
        protected CompleteCheckoutService $completeCheckoutService,
    ) {}

    public function show(Transaction $transaction)
    {
        return view('src.cashier.receipt', ['transaction' => $transaction]);
    }
}
