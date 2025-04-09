<?php

namespace App\Services;

use App\Enum\RentStatus;
use App\Exceptions\InternalException;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\ProductRentedStatus;
use Illuminate\Support\Facades\{DB, Log};

class ProductRentService
{   
    public function __construct() {}

    /**
     * Request a product rent or update the status of an existing product rent.
     *
     * @param \Illuminate\Http\Request $request
     * @return array product rent data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    public function requestProductRent($request) {
        try {
            return DB::transaction(function () use ($request) {
                $productRent = $this->createProductRent($request);

                if (empty($productRent)) {
                    throw new \RuntimeException('No product were rented');
                }

                return ['product_rent' => $productRent, 'message' => 'Rented successfully'];
            });
        } catch (\Exception $e) {
            Log::error("Product rent failed: " . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
            return ['product_rent' => $productRent, 'message' => 'Failed to rent product'];
        }
    }

    /**
     * Create a new product rent or update the status of an existing product rent.
     *
     * @param \Illuminate\Http\Request $request
     * @return array product rent data
     */
    private function createProductRent($request) : array
    {   
        if (!ProductRentedStatus::exists()) {
            $this->generateProductRentStatus();
        }

        $validated = $request->safe();
        $productRent = $this->handleProductRent(
            $validated->only(['customer_rented_id', 'rent_details_id']),
            ['rent_status' => RentStatus::RENTED->value]
        );

        return compact('productRent');
    }

    public function execProductRent($request)
    {
        return $this->createProductRent($request);
    }

    /**
     * Handle the product rent creation.
     *
     * @param array $data
     * @param int $relation id
     * @return \App\Models\Transactions\ProductRent
     */
    private function handleProductRent(array $data, $relation) : ProductRent
    {
        return ProductRent::create([
            'customer_rented_id' => $data['customer_rented_id'],
            'rent_details_id' => $data['rent_details_id'],
            'product_rented_status_id' => $relation['rent_status'],
        ]);
    }

    /**
     * Generate product rent statuses if they do not exist.
     *
     * @return void
     */
    private function generateProductRentStatus() : void
    {
        $existingStatuses = array_map('strtolower', ProductRentedStatus::pluck('status_name')->toArray());
        $allStatuses = array_map(fn($status) => strtolower($status->label()), RentStatus::cases());

        if (count(array_diff($allStatuses, $existingStatuses))) {
            foreach (RentStatus::cases() as $status) {
                ProductRentedStatus::updateOrCreate(['id' => $status->value, 'condition_name' => $status->label()]);
            }
        }
    }
}