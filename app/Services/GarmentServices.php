<?php

namespace App\Services;

use App\Enum\Condition;
use App\Http\Requests\GarmentRequest;
use App\Models\ConditionStatus;
use App\Models\Garment;
use App\Models\Size;

class GarmentServices
{
    public function createGarment(GarmentRequest $request): array
    {
        $validated = $request->safe();

        // creates new status first
        $this->generateStatus();

        // creates new size
        $size = $this->handleSize($validated->only([
            'measurement', 'length', 'width'
        ]));

        // creates new garment
        $garment = $this->handleGarment($validated->only([
            'product_id', 'additional_description', 'poster'
        ]), 
        ['sizes_id' => $size->id]);

        // return as arrays
        return compact('garment', 'size');
    }

    private function handleGarment(array $garmentProduct): Garment
    {
        return Garment::firstOrCreate(
            [
                'product_id' => $garmentProduct['product_id'],
                'additional_description' => $garmentProduct['additional_description'],
                'poster' => $garmentProduct['poster'],
                'size_id' => $garmentProduct['size_id'],
            ]
        );
    }

    private function handleSize(array $sizeData) : Size
    {
        return Size::firstOrCreate(
            [
                'measurement' => $sizeData['measurement'],
                'length' => $sizeData['length'],
                'width' => $sizeData['width'],
            ]
        );
    }

    private function generateStatus() : void
    {   
        // get all existing conditions
        $existingConditions = ConditionStatus::value('condition')->toArray();
        $allConditions = array_map( fn($condition) => $condition
            ->label(), 
            Condition::cases()
        );

        // counts the difference between existing and all conditions
        if (count(array_diff($allConditions, $existingConditions)) > 0) {
            foreach (Condition::cases() as $condition) {
                ConditionStatus::firstOrCreate([
                    'condition' => $condition->label()
                ]);
            }   
        }

        return;
    }
}
