<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController
{
    public function __construct(protected TransactionService $transactionService) {}

    public function store(Request $request)
    {
        $transaction = $this->transactionService->requestTransaction($request);
        return response()->json([
            'message' => $transaction['message'],
            'transaction' => $transaction['transaction']
        ]);
    }
}
