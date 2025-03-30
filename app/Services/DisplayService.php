<?php

namespace App\Services;

use App\Enum\ProductStatus;
use App\Exceptions\InternalException;
use App\Http\Requests\GarmentProductRequest;
use App\Models\{DisplayProduct, ProductStatus as ModelProductStatus};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisplayService
{   
    public function requestDisplayGarment(GarmentProductRequest $request)
    {   
        try {
            return DB::transaction(function () use ($request) {
                $display = $this->createDisplayGarment($request);

                if (empty($display)) {
                    throw new \RuntimeException('No garment were added');
                }

                return ['display' => $display, 'message' => 'Display added successfully'];
            });
        } catch (\Exception $e) {
            Log::error("Display creation failed: " . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function createDisplayGarment(GarmentProductRequest $request)
    {
        $validated = $request->safe();

        if(!ModelProductStatus::exists()) {
            $this->generateStatus();
        }

        $display = $this->handleDisplayGarment($validated->only(
                ['user_id', 'garment_id']
            ), 
            ['product_status_id' => ProductStatus::UNAVAILABLE->value]
        );

        return compact('display');
    }

    private function generateStatus() : void
    {
        // get all existing conditions
        $existingConditions = ModelProductStatus::value('status_name')->toArray();
        $allConditions = array_map( fn($condition) => $condition
            ->label(), 
            ProductStatus::cases()
        );

        // counts the difference between existing and all conditions
        if (count(array_diff($allConditions, $existingConditions)) > 0) {
            foreach (ProductStatus::cases() as $condition) {
                ModelProductStatus::firstOrCreate([
                    'condition' => $condition->label()
                ]);
            }   
        }

        return;
    }

    private function handleDisplayGarment(array $data, $relations) : DisplayProduct
    {
        return DisplayProduct::create([
            'user_id' => $data['user_id'],
            'garment_id' => $data['garment_id'],
            'product_status_id' => $data['product_status_id']
        ]);
    }
}
