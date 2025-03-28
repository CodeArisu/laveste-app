<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\GarmentRequest;
use App\Models\Garment;
use App\Services\GarmentServices;
use Illuminate\Support\Facades\DB;

class GarmentController extends ApiBaseController
{
    public function store(GarmentRequest $request, GarmentServices $garmentServices)
    {
        try {
            return DB::transaction(function () use ($request, $garmentServices) {
                $garments = $garmentServices->createGarment($request);

                array_walk($garments, fn($garment) => 
                    $this->isChecked($garment, 'Failed to create garment!')
                );

                return $this->sendSuccess('Created Successfully!');
            });
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }

    public function delete(Garment $garment)
    {
        try {
            $garment->delete();

            return $this->sendSuccess('Deleted Successfully!');
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }
}
