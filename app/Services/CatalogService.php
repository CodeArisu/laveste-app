<?php

namespace App\Services;

use App\Enum\ProductStatus;
use App\Http\Requests\GarmentProductRequest;
use App\Models\{Catalog, Statuses\DisplayStatus};
use Illuminate\Support\Facades\{DB, Log};

class CatalogService
{
    public function requestDisplayGarment(GarmentProductRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $display = $this->createDisplayGarment($request);

                if (empty($display)) {
                    throw new \RuntimeException('No garment were added');
                }

                return ['display' => $display, 'message' => 'Added to display successfully'];
            });
        } catch (\Exception $e) {
            Log::error('Display creation failed: ' . $e->getMessage());
            return ['display' => $display, 'message' => 'Failed to add to Display'];
        }
    }

    private function createDisplayGarment(GarmentProductRequest $request)
    {
        $validated = $request->safe();

        if (!DisplayStatus::exists()) {
            $this->generateStatus();
        }

        $display = $this->handleDisplayGarment($validated->only(['garment_id']), [
            'user_id' => $request->user()->id,
            'product_status_id' => ProductStatus::UNAVAILABLE->value,
        ]);

        return compact('display');
    }

    private function generateStatus(): void
    {
        // get all existing conditions
        $existingConditions = DisplayStatus::value('status_name')->toArray();
        $allConditions = array_map(fn($condition) => $condition->label(), ProductStatus::cases());

        // counts the difference between existing and all conditions
        if (count(array_diff($allConditions, $existingConditions)) > 0) {
            foreach (ProductStatus::cases() as $condition) {
                DisplayStatus::firstOrCreate([
                    'condition' => $condition->label(),
                ]);
            }
        }

        return;
    }

    private function handleDisplayGarment(array $data, $relations): Catalog
    {
        return Catalog::create([
            'user_id' => $relations['user_id'],
            'garment_id' => $data['garment_id'],
            'product_status_id' => $relations['product_status_id'],
        ]);
    }
}
