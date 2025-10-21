<?php

namespace App\Http\Controllers;

use App\Http\Requests\GarmentRequest;
use App\Http\Resources\GarmentResource;
use App\Models\Garments\Garment;
use App\Services\GarmentService;

class GarmentController extends BaseController
{
    public function __construct(protected GarmentService $garmentService) {}

    public function index()
    {
        $garments = Garment::with(['product', 'size', 'condition'])->get();

        return view('src.dashboard.pages.garments', ['garments' => GarmentResource::collection($garments)]);
    }

    public function store(GarmentRequest $request)
    {
        $createdGarment = $this->garmentService->requestCreateGarment($request);

        return redirect()->route('dashboard.garment.index')->with('success', $createdGarment['message']);
    }

    public function edit($garmentId)
    {
        $garment = Garment::with(['product', 'size', 'condition'])->findOrFail($garmentId);
        return view('src.dashboard.partials.garment-edit-details', compact('garment'));
    }

    public function show($garmentId)
    {
        // Eager load the relationships to avoid N+1 query problem
        $garment = Garment::with(['product', 'size', 'condition'])->findOrFail($garmentId);
        return view('src.dashboard.partials.garment-details', compact('garment'));
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
