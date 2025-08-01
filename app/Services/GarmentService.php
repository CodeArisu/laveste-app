<?php

namespace App\Services;

use App\Enum\ConditionStatus;
use App\Events\GarmentCreated;
use App\Exceptions\GarmentException;
use App\Http\Requests\GarmentRequest;
use App\Models\Garments\{Condition, Size, Garment};
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Storage;

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

                event(new GarmentCreated($garments, $request->user()));

                return ['garment' => $garments, 'message' => 'Garment added successfully'];
            });
        } catch (\Exception $e) {
            report($e);
            dd($e);
            throw GarmentException::garmentCreateFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw GarmentException::garmentNotFound();
        } catch (QueryException $e) {
            // Database query errors (constraint violations, etc.)
            report($e);
            throw GarmentException::garmentNotFound();
        } catch (ValidationException $e) {
            // If any validation fails (though GarmentRequest should handle most)
            throw GarmentException::garmentValidationFailed();
        } catch (\RuntimeException $e) {
            // Your custom runtime exceptions
            dd($e);
            throw GarmentException::garmentCreateFailed();
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
            report($e);
            throw GarmentException::garmentUpdateFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw GarmentException::garmentNotFound();
        } catch (QueryException $e) {
            // Database query errors (constraint violations, etc.)
            report($e);
            throw GarmentException::garmentNotFound();
        } catch (ValidationException $e) {
            // If any validation fails (though GarmentRequest should handle most)
            throw GarmentException::garmentValidationFailed();
        } catch (\RuntimeException $e) {
            // Your custom runtime exceptions
            throw GarmentException::garmentUpdateFailed();
        }
    }

    public function requestDeleteGarment(Garment $garment)
    {
        $garmentName = $garment->product->product_name;
        try {
            $garment->size->deleteOrFail();
            $garment->deleteOrFail();

            return [
                'message' => 'Deleted Success',
                'garment' => $garmentName
            ];
        } catch (\Exception $e) {
            report($e);
            throw GarmentException::garmentDeleteFailed();
        } catch (ModelNotFoundException $e) {
            report($e);
            throw GarmentException::garmentNotFound();
        }
    }

    private function createGarment(GarmentRequest $request): Garment
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

        if(isset($validated['poster'])) {
            $poster = $this->handlePosterImage($validated['poster']);
            $validated['poster'] = $poster['poster_name'];
            $posterPath = $poster['poster_path'];
        }

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
        
        return $garment;
    }

    private function updateGarment(GarmentRequest $request, Garment $garment) : Garment
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

        if(isset($validated['poster'])) {
            $poster = $this->handlePosterImage($validated['poster']);
            $validated['poster'] = $poster['poster_name'];
            $posterPath = $poster['poster_path'];
        }

        $garment = $this->updateOrKeepGarment($garment, array_merge(
            $validated->only([
                'additional_description', 
                'poster', 
                'rent_price',
            ]), [ // related data
                 'size_id' => $size->id, 
                 'condition_id' => $garmentCondition
                ]
            )
        );

        return $garment;
    }

    private function handlePosterImage($poster)
    {
        // Validate the uploaded file
        if (!$poster || !$poster->isValid()) {
            throw new \Exception('Invalid file upload - file missing or corrupted');
        }
    
        try {
            // Generate a unique filename with original extension
            $posterName = time().'_'.Str::random(10).'.'.$poster->getClientOriginalExtension();
            
            // Store file explicitly on the 'public' disk
            $path = Storage::disk('public')->putFileAs(
                'garments',
                $poster,
                $posterName
            );
    
            // Verify the file was actually stored
            if (!Storage::disk('public')->exists('garments/'.$posterName)) {
                throw new \Exception('File storage verification failed');
            }
    
            return [
                'poster_name' => $posterName,
                'poster_path' => Storage::disk('public')->url('garments/'.$posterName)
            ];
    
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Image upload failed: '.$e->getMessage());
            throw new \Exception('Failed to process image upload');
        }
    }

    private function updateOrKeepGarment(?Garment $garment, array $garmentData) : Garment
    {   
        if (!$garment || $this->garmentDataChange($garment, $garmentData)) {
            return $this->handleUpdateGarment($garmentData, $garment);
        }

        return $garment;
    }

    private function garmentDataChange(Garment $garment, array $garmentData) : bool
    {   
        return  $garment->additional_description !== $garmentData['additional_description'] || 
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
                'poster' => $garmentData['poster'] ?? null,
                'size_id' => $relations['size_id'],
                'condition_id' => $relations['condition_id'],
            ]
        );
    }

    private function getProduct($garmentData) : Product
    {   
        $product = Product::findOrFail($garmentData['product_id']) ?? Garment::where('product_id', $garmentData['product_id'])->findOrFail();
        if (!$product) {
            throw new \Exception("Invalid product_id: {$garmentData['product_id']}");
        }
        return $product;
    }

    private function handleUpdateGarment(array $garmentData, $garment) : Garment
    {
        $oldSizeId = $garment->size_id;
        $garment->update([
                'rent_price' => $garmentData['rent_price'],
                'additional_description' => $garmentData['additional_description'],
                'poster' => $garmentData['poster'],
                'size_id' => $garmentData['size_id'],
                'condition_id' => $garmentData['condition_id'],
                'updated_at' => now()
            ]
        );
        $this->deleteOldSize($oldSizeId, $garmentData);
        return $garment;
    }

    private function deleteOldSize($oldSizeId, $garmentData)
    {
        if ($oldSizeId !== $garmentData['size_id']) {
            // finds the size added if exists
            $sizeInUse = Garment::where('size_id', $oldSizeId)->exists();

            // if the use data doesnt exists delete
            if (!$sizeInUse)
            {
                Size::find($oldSizeId)->delete();
            }
        }
    }

    private function handleSize(array $sizeData) : Size
    {
        return Size::firstOrCreate([
                'measurement' => $sizeData['measurement'],
                'length' => $sizeData['length'] ?? 0,
                'width' => $sizeData['width'] ?? 0,
            ]
        );
    }

    private function handleUpdateSize(array $sizeData) : Size
    {
        return Size::updateOrCreate([
                'measurement' => $sizeData['measurement'],
                'length' => $sizeData['length'],
                'width' => $sizeData['width'],
                'updated_at' => now()
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
                Condition::updateOrCreate([
                    'id' => $status->value, 
                    'condition_name' => $status->label()
                ]);
            }
        }
    }

    protected function validateUpdateResults($updatedData): void
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

    public function getGarmentData(Garment $garment): array
    {
        return [
            'id' => $garment->id,
            'product_id' => $garment->product_id,
            'additional_description' => $garment->additional_description,
            'poster' => $garment->poster,
            'rent_price' => $garment->rent_price,
            'size' => $garment->size,
            'condition' => $garment->condition,
            'display_attributes' => $this->transformForDisplayGarment($garment),
        ];
    }

    protected function transformForDisplayGarment(Garment $garment): array
    {
       return [
            'formatted_price' => '₱' . number_format($garment->rent_price, 2),
       ];
    }
}