<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\CatalogRequest;
use App\Models\Garments\Garment;
use App\Services\CatalogService;

class CatalogController extends ApiBaseController
{
    public function __construct(protected CatalogService $displayService){}

    public function store(CatalogRequest $request, Garment $garment) 
    {
        $display = $this->displayService->requestDisplayGarment($request, $garment);
        return $this->sendResponse($display['message'], $display['display']);
    }

    public function update()
    {
        // should only be the status
    }
}
