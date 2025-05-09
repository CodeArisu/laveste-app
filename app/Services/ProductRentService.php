<?php

namespace App\Services;

use App\Enum\RentStatus;
use App\Http\Requests\CustomerDetailsRequest;
use App\Models\Statuses\ProductRentedStatus;
use App\Models\Transactions\ProductRent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\{DB, Log};

class ProductRentService
{   
    public function __construct(protected CustomerDetailService $customerDetailService) {}

    /**
     * Request a product rent or update the status of an existing product rent.
     *
     * @param \Illuminate\Http\Request $request
     * @return array product rent data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    public function requestProductRent(CustomerDetailsRequest $request) 
    {
        $validated = $request->validated();
        Session::put('checkout.customer_data', $validated);
    }

    public function execProductRent($customerData, $catalogData)
    {  
        return $this->createProductRent($customerData, $catalogData);
    }

    /**
     * Create a new product rent or update the status of an existing product rent.
     *
     * @param \Illuminate\Http\Request $request
     * @return array product rent data
     */
    private function createProductRent($request, $catalogData) : ProductRent
    {   
        if (!ProductRentedStatus::exists()) {
            $this->generateProductRentStatus();
        }

        $customerDetails = $this->customerDetailService->executeCustomerRent($request);
        $rentStatus = $this->checkForProductRentedStatus(RentStatus::RENTED->value);

        return $this->handleProductRent(
            [
                'customer_rented_id' => $customerDetails['customerRent']['id'],
                'rent_details_id' => $customerDetails['customerRentDetails']['id'],
            ],[    
                'catalog_product_id' => $catalogData->id,
                'rent_status' => $rentStatus,
            ]
        );
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
            'catalog_product_id' => $relation['catalog_product_id'],
            'product_rented_status_id' => $relation['rent_status'] ?? RentStatus::RENTED->value,
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

        if (count(array_diff($allStatuses, $existingStatuses)) > 0) {
            // ProductRentedStatus::truncate();
            foreach (RentStatus::cases() as $status) {
                ProductRentedStatus::updateOrCreate([
                    'id' => $status->value, 
                    'status_name' => $status->label()
                ]);
            }
        }
    }

    private function checkForProductRentedStatus($statusID)
    {   
        if (!ProductRentedStatus::where('id', $statusID)->exists()) {
            throw new \RuntimeException("Product rented status with ID {$statusID} not found");
        }

        return ProductRentedStatus::find($statusID)->id ?? null;
    }
}