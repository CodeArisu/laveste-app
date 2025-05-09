<?php

namespace App\Listeners;

use App\Events\TransactionSession;
use App\Models\Transactions\Transaction;
use App\Services\CompleteCheckoutService;
use App\Services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTransaction
{
    protected $transactionService;
    protected $completeCheckoutService;

    public function __construct(TransactionService $transactionService, CompleteCheckoutService $completeCheckoutService)
    {
        $this->transactionService = $transactionService;
        $this->completeCheckoutService = $completeCheckoutService;
    }
    
    /**
     * Handle the event.
     */
    public function handle(TransactionSession $event): void
    {
        try {
            // 2nd flag
            // dd($event->transactionData);
            $checkoutData = $this->transactionService->getCheckoutData($event->transactionData, $event->catalogId);
            // 3rd flag
            //  dd($checkoutData);
            $this->completeCheckoutService->completeCheckout($checkoutData);
        } catch (\Exception $e) {
            // Handle the exception
            \Log::error('Failed to create transaction: ' . $e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
    }
}
