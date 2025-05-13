<?php

namespace App\Listeners;

use App\Events\CatalogStatus;
use App\Services\CatalogService;
use App\Services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCatalogStatus
{
    public function __construct(
        protected TransactionService $transactionService, 
        protected CatalogService $catalogService
    ){}

    public function handle(CatalogStatus $event): void
    {   
        try {
            $newStatus = $this->transactionService->getUpdatedStatus($event->status, $event->catalogId);
            $updatedStatus = $this->catalogService->updateCatalogItemStatus($newStatus);
            \Log::info($updatedStatus['message']);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}