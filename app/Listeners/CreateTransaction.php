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
    public function __construct(
        protected TransactionService $transactionService, 
        protected CompleteCheckoutService $completeCheckoutService
    ){}
    
    /**
     * Handle the event.
     */
    public function handle(TransactionSession $event): void
    {
        try {
            $checkoutData = $this->transactionService->getCheckoutData($event->transactionData, $event->catalogId);
            $completedData = $this->completeCheckoutService->completeCheckout($checkoutData);
            $event->response = $completedData;
        } catch (\Exception $e) {
            // Handle the exception
            \Log::error('Failed to create transaction: ' . $e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
    }
}
