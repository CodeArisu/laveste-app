<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\GarmentRequest;
use App\Models\Garment;
use App\Services\GarmentService;

class GarmentController extends ApiBaseController
{   
    public function __construct(protected GarmentService $garmentService) {}

    public function store(GarmentRequest $request)
    {
        $garment = $this->garmentService->requestCreateGarment($request);
        return $this->sendCreateResponse($garment['message'], $garment['garment']);
    }

    public function update(GarmentRequest $request, Garment $garment)
    {
        $updatedGarment = $this->garmentService->requestUpdateGarment($request, $garment);
        $this->sendUpdateResponse($updatedGarment['message'], $updatedGarment['garment'], $updatedGarment['updated_fields']);
    }

    public function delete(Garment $garment)
    {
        $garment = $this->garmentService->requestDeleteGarment($garment);
        return $this->sendDeleteResponse($garment['message'], $garment['deleted'], $garment['garment_name']);
    }
}
