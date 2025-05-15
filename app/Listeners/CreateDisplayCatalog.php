<?php

namespace App\Listeners;

use App\Events\GarmentCreated;
use App\Services\CatalogService;
use App\Services\GarmentService;

class CreateDisplayCatalog
{
    public function __construct(
        protected CatalogService $catalogService, 
        protected GarmentService $garmentService
    ){}

    /**
     * Handle the event.
     */
    public function handle(GarmentCreated $event): void
    {   
        try {
            $garmentData = $this->garmentService->getGarmentData($event->garment);
            $this->catalogService->createDisplayGarment($garmentData, $event->user);
        } catch (\Exception $e) {
            // Handle the exception
            \Log::error('Failed to create display catalog: ' . $e->getMessage());
        }
    }
}
