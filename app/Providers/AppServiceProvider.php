<?php

namespace App\Providers;

use App\Events\GarmentCreated;
use App\Listeners\CreateDisplayCatalog;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{   
    protected $listen = [
        GarmentCreated::class => [
            CreateDisplayCatalog::class,
        ],
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // JsonResource::withoutWrapping();
    }
}
