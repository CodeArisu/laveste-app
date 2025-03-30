<?php

namespace App\Services;

use App\Enum\Condition;
use App\Exceptions\InternalException;
use App\Http\Requests\GarmentRequest;
use App\Models\{ConditionStatus, Size, Garment};
use Illuminate\Support\Facades\{DB, Log};

class GarmentService
{    
    public function requestCreateGarment(GarmentRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $garments = $this->createGarment($request);

                if (empty($garments)) {
                    throw new \RuntimeException('No garment were added');
                }

                foreach ($garments as $garment) {
                    if (!Garment::exists()) {
                        throw new \RuntimeException("Failed to create {$garment}");
                    }
                }

                return ['garment' => $garments, 'message' => 'Garment added successfully'];
            });
        } catch (\Exception $e) {
            Log::error("Garment creation failed: " . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function requestUpdateGarment(GarmentRequest $request, Garment $garment)
    {
        try {
            return DB::transaction(function () use ($request, $garment) {
                $updatedGarments  = $this->updateGarment($request, $garment);

                $this->validateUpdateResults($updatedGarments);

                return [
                    'garment' => $garment->fresh(),
                    'updated_fields' => array_keys($updatedGarments),
                    'message' => 'Updated Success'
                ];
            });
        } catch (\Exception $e) {
            Log::error("Product update failed - ID: {$garment->id}", [
                'error' => $e->getMessage(),
                'request' => $request->validated(),
            ]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function requestDeleteGarment(Garment $garment)
    {
        $garmentName = $garment->product->product_name;
        try {
            $garment->deleteOrFail();

            return [
                'deleted' => true,
                'message' => 'Deleted Success',
                'garment_name' => $garmentName
            ];
        } catch (\Exception $e) {
            Log::error("Product delete failed - ID: {$garment->id}", [
                'error' => $e->getMessage()
            ]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);;
        }
    }

    private function createGarment(GarmentRequest $request): array
    {
        $validated = $request->safe();

        // creates new status first
        if (!ConditionStatus::exists()) {
            $this->generateStatus();
        }
       
        // creates new size
        $size = $this->handleSize($validated->only([
            'measurement', 'length', 'width'
        ]));

        // creates new garment
        $garment = $this->handleGarment($validated->only([
            'product_id', 'additional_description', 'poster', 'renting_price'
        ]), 
            ['sizes_id' => $size->id, 'condition' => Condition::UNAVAILABLE->value] // default condition UNAVAILABLE
        );

        // return as arrays
        return compact('garment', 'size');
    }

    private function updateGarment(GarmentRequest $request, Garment $garment) : array
    {
        $validated = $request->safe();

        $size = $this->updateOrKeepSize($garment->size, 
            $validated->only([
                'measurement', 
                'length', 
                'width'
            ])
        );

        $garment = $this->updateOrKeepGarment($garment, array_merge(
            $validated->only([
                'product_id', 
                'additional_description', 
                'poster', 
                'renting_price'
            ]), [
                 'sizes_id' => $size->id, 
                 'condition' => Condition::UNAVAILABLE->value
                ]
            )
        );

        return compact('size', 'garment');
    }

    private function updateOrKeepGarment(?Garment $garment, array $garmentData) : Garment
    {
        if (!$garment || $this->garmentDataChange($garment, $garmentData)) {
            return $this->handleUpdateGarment($garmentData);
        }

        return $garment;
    }

    private function garmentDataChange(Garment $garment, array $garmentData) : bool
    {
        return $garment->product_id !== $garmentData['product_id'] || $garment->additional_description !== $garmentData['additional_description'] || 
                $garment->poster !== $garmentData['poster'] || $garment->renting_price !== $garmentData['renting_price'] || 
                $garment->size_id !== $garmentData['garment_size'] || $garment->condition !== $garmentData['condition'];
    }

    private function updateOrKeepSize(?Size $size, array $sizeData) : Size
    {
        if (!$size || $this->sizeDataChange($size, $sizeData)) {
            return $this->handleUpdateSize($sizeData);
        }
        
        return $size;
    }

    private function sizeDataChange(Size $size, array $sizeData) : bool
    {
        return $size->measurement !== $sizeData['measurement'] || $size->length !== $sizeData['length'] ||
                $size->width !== $sizeData['width'];
    }

    private function handleGarment(array $garmentData): Garment
    {
        return Garment::firstOrCreate(
            [
                'product_id' => $garmentData['product_id'],
                'rent_price' => $garmentData['rent_price'],
                'additional_description' => $garmentData['additional_description'],
                'poster' => $garmentData['poster'],
                'size_id' => $garmentData['size_id'],
                'condition' => $garmentData['condition']
            ]
        );
    }

    private function handleUpdateGarment(array $garmentData) : Garment
    {
        return Garment::createOrUpdate(
            [
                'product_id' => $garmentData['product_id'],
                'rent_price' => $garmentData['rent_price'],
                'additional_description' => $garmentData['additional_description'],
                'poster' => $garmentData['poster'],
                'size_id' => $garmentData['size_id'],
                'condition' => $garmentData['condition']
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

    private function handleUpdateSize(array $sizeData) : Size
    {
        return Size::createOrUpdate(
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

    protected function validateUpdateResults(array $updatedData): void
    {
        foreach ($updatedData as $field => $success) {
            if (!$success) {
                throw new \RuntimeException("Failed to update {$field}");
            }
        }
    }
}