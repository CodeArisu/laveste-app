<?php

namespace App\Http\Controllers\Api;

use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class CashierController
{   
    public function __construct(protected TransactionService $transactionService)
    {}

    public function rentalsIndex() 
    {   
        $productRents = ProductRent::with(['catalog.garment', 'customerRent.customerDetail'])->get();
        return view('src.cashier.home', 
            ['productRents' => $productRents,]
        );
    }

    public function transactionIndex()
    {
        $transactions = Transaction::all();
        return view('src.cashier.transaction', ['transactions' => $transactions]);
    }
}
