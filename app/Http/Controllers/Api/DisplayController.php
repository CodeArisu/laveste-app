<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\GarmentProductRequest;
use App\Services\DisplayService;

class DisplayController extends ApiBaseController
{
    public function __construct(protected DisplayService $displayService){}

    public function store(GarmentProductRequest $request) 
    {
        $display = $this->displayService->requestDisplayGarment($request);
        return $this->sendResponse($display['message'], $display['display']);
    }

    public function update()
    {
        //
    }
}
