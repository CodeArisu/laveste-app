<?php

namespace App\Services;

use App\Enum\ConditionStatus;
use App\Exceptions\InternalException;
use App\Http\Requests\GarmentRequest;
use App\Models\{Product, Condition, Size, Garment};
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
            return ['garment' => $garments, 'message' => 'Failed to add garment'];
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
                    'message' => 'Garment updated successfully'
                ];
            });
        } catch (\Exception $e) {
            Log::error("Product update failed - ID: {$garment->id}", [
                'error' => $e->getMessage(),
                'request' => $request->validated(),
            ]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['garment' => $garment, 'message' => 'Failed to update garment'];
        }
    }

    public function requestDeleteGarment(Garment $garment)
    {
        $garmentName = $garment->product->product_name;
        try {
            $garment->deleteOrFail();

            return [
                'message' => 'Deleted Success',
                'garment' => $garmentName
            ];
        } catch (\Exception $e) {
            Log::error("Product delete failed - ID: {$garment->id}", [
                'error' => $e->getMessage()
            ]);
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['garment' => $garment, 'message' => 'Failed to delete garment'];
        }
    }

    private function createGarment(GarmentRequest $request): array
    {
        $validated = $request->safe();
        // creates new status first if not exists
        if (!Condition::exists()) {
            $this->generateCondition();
        }

        // creates new size
        $size = $this->handleSize($validated->only([
            'measurement', 
            'length', 
            'width'
        ]));

        $garmentCondition = $this->checkForConditionEntry($validated);
    
        // creates new garment
        $garment = $this->handleGarment($validated->only([
            'product_id', 
            'additional_description', 
            'poster', 
            'rent_price',
        ]), [ // related data
            'size_id' => $size->id,
            'condition_id' => $garmentCondition
        ]);

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

        $garmentCondition = $this->checkForConditionEntry($validated);

        $garment = $this->updateOrKeepGarment($garment, array_merge(
            $validated->only([
                'product_id', 
                'additional_description', 
                'poster', 
                'rent_price',
            ]), [ // related data
                 'size_id' => $size->id, 
                 'condition_id' => $garmentCondition
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
                $garment->size_id !== $garmentData['size_id'] || $garment->condition !== $garmentData['condition'];
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

    private function handleGarment(array $garmentData, array $relations): Garment
    {   
        $product = $this->getProduct($garmentData);
        return Garment::firstOrCreate([
                'product_id' => $product->id,
                'rent_price' => $garmentData['rent_price'],
                'additional_description' => $garmentData['additional_description'],
                'poster' => $garmentData['poster'],
                'size_id' => $relations['size_id'],
                'condition_id' => $relations['condition_id']
            ]
        );
    }

    private function getProduct($garmentData) : Product
    {
        $product = Product::findOrFail($garmentData['product_id']);
        if (!$product) {
            throw new \Exception("Invalid product_id: {$garmentData['product_id']}");
        }
        return $product;
    }

    private function handleUpdateGarment(array $garmentData) : Garment
    {   
        return Garment::updateOrCreate([
                'product_id' => $garmentData['product_id'],
                'rent_price' => $garmentData['rent_price'],
                'additional_description' => $garmentData['additional_description'],
                'poster' => $garmentData['poster'],
                'size_id' => $garmentData['size_id'],
                'condition_id' => $garmentData['condition_id']
            ]
        );
    }

    private function handleSize(array $sizeData) : Size
    {
        return Size::firstOrCreate([
                'measurement' => $sizeData['measurement'],
                'length' => $sizeData['length'],
                'width' => $sizeData['width'],
            ]
        );
    }

    private function handleUpdateSize(array $sizeData) : Size
    {
        return Size::updateOrCreate([
                'measurement' => $sizeData['measurement'],
                'length' => $sizeData['length'],
                'width' => $sizeData['width'],
            ]
        );
    }

    private function generateCondition() : void
    {   
        // get all existing statuses
        $existingStatuses = array_map('strtolower', Condition::pluck('condition_name')->toArray());
        $allStatuses = array_map(fn($status) => strtolower($status->label()), ConditionStatus::cases());

        // counts the difference between existing and all statuses
        if (count(array_diff($allStatuses, $existingStatuses))) {
            foreach (ConditionStatus::cases() as $status) {
                Condition::updateOrCreate(['id' => $status->value, 'condition_name' => $status->label()]);
            }
        }
    }

    protected function validateUpdateResults(array $updatedData): void
    {
        foreach ($updatedData as $field => $success) {
            if (!$success) {
                throw new \RuntimeException("Failed to update {$field}");
            }
        }
    }

    private function checkForConditionEntry($validated)
    {
        return $validated->has('condition_id') && $validated->filled('condition_id') 
        ? $validated->only('condition_id')['condition_id'] 
        : ConditionStatus::OK->value;
    }
}