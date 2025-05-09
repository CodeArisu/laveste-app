<?php

namespace App\Http\Controllers\Api;

use App\Models\Transactions\Transaction;
use Illuminate\Http\Request;

class CashierController
{
    public function index() 
    {   
        $transactions = Transaction::all();
        return view('src.cashier.transaction', ['transactions' => $transactions]);
    }
}
