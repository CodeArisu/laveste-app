<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\GarmentRequest;
use App\Http\Resources\GarmentResource;
use App\Models\Garments\Garment;
use App\Services\GarmentService;

class GarmentController extends ApiBaseController
{   
    public function __construct(protected GarmentService $garmentService) {}
    
    public function index()
    {
        $garments = Garment::with(['product', 'size', 'condition'])->get();
        return view('src.admin.garment', ['garments' => GarmentResource::collection($garments)]);
    }

    public function store(GarmentRequest $request)
    {   
        $createdGarment = $this->garmentService->requestCreateGarment($request);
        
        return redirect()->route('dashboard.garment.index')->with('success', $createdGarment['message']);
    }

    public function show(Garment $garment)
    {
        // Eager load the relationships to avoid N+1 query problem
        $garments = $garment->with(['product', 'size', 'condition'])->find($garment);
        return GarmentResource::collection($garments);
    }

    public function update(GarmentRequest $request, Garment $garment)
    {
        $updatedGarment = $this->garmentService->requestUpdateGarment($request, $garment);
        return $this->sendResponse($updatedGarment['message'], $updatedGarment['garment']);
    }

    public function destroy(Garment $garment)
    {
        $deletedGarment = $this->garmentService->requestDeleteGarment($garment);
        return $this->sendResponse($deletedGarment['message'], $deletedGarment['garment']);
    }
}
