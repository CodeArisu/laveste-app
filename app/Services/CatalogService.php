<?php

namespace App\Services;

use App\Enum\ProductStatus;
use App\Http\Requests\CatalogRequest;
use App\Models\Garments\Garment;
use App\Models\{Catalog, Statuses\DisplayStatus};
use Illuminate\Support\Facades\{DB, Log};

class CatalogService extends BaseServicesClass
{
    public function createDisplayGarment($garment, $user)
    {
        if (!DisplayStatus::exists()) {
            $this->generateStatus();
        }
        
        $this->handleDisplayGarment([
            'user_id' => $user->id,
            'garment_id' => $garment['id'],
            'product_status_id' => ProductStatus::AVAILABLE->value,
        ]);
    }

    private function generateStatus(): void
    {
        $existingConditions = array_map('strtolower', DisplayStatus::pluck('status_name')->toArray());
        $allConditions = array_map(fn($condition) => $condition->label(), ProductStatus::cases());

        // counts the difference between existing and all conditions
        if (count(array_diff($allConditions, $existingConditions))) {
            foreach (ProductStatus::cases() as $status) {
                DisplayStatus::firstOrCreate([
                    'id' => $status->value,
                    'status_name' => $status->label(),
                ]);
            }
        }
        return;
    }

    private function handleDisplayGarment(array $relations): Catalog
    {
        return Catalog::create([
            'user_id' => $relations['user_id'],
            'garment_id' => $relations['garment_id'],
            'product_status_id' => $relations['product_status_id'],
        ]);
    }
}