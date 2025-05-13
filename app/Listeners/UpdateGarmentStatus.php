<?php

namespace App\Listeners;

use App\Events\GarmentReturned;
use App\Services\CatalogService;
use App\Services\ProductRentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateGarmentStatus
{
    public function __construct(
        protected CatalogService $catalogService,
        protected ProductRentService $productRentService,
    ){}

    public function handle(GarmentReturned $event): void
    {
        // pass catalog

        // pass rented
    }
}
