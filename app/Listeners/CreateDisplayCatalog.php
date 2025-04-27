<?php

namespace App\Listeners;

use App\Events\GarmentCreated;
use App\Services\CatalogService;
use App\Services\GarmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDisplayCatalog
{
    protected $catalogService;
    protected $garmentService;

    public function __construct(CatalogService $catalogService, GarmentService $garmentService)
    {
        $this->catalogService = $catalogService;
        $this->garmentService = $garmentService;
    }

    /**
     * Handle the event.
     */
    public function handle(GarmentCreated $event): void
    {
        $garmentData = $this->garmentService->getGarmentData($event->garment);
        $this->catalogService->createDisplayGarment($garmentData);
    }
}
