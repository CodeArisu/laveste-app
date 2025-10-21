<?php

namespace App\Services;

use App\Models\Transactions\CustomerDetail;
use App\Models\Transactions\CustomerRent;
use App\Models\Transactions\RentDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class CustomerDetailService
{
    public function __construct() {}

    public function executeCustomerRent($customerData)
    {
        $customerRent = $this->createCustomerRent($customerData);
        // checks if customer rent was created
        foreach ($customerRent as $rent) {
            if (empty($rent)) {
                throw new \RuntimeException('No customer were rented');
            }
        }

        return $customerRent;
    }

    public function requestUserDetails($request)
    {
        try {
            $customerDetails = $this->filterCustomerDetail($request);
            return $this->handleCustomerDetail($customerDetails);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Request customer rent creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array customer rent data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    protected function createCustomerRent($customerData)
    {
        $sessionCustomerDetails = $this->filterCustomerDetail($customerData);

        $sessionCustomerRentDetails = $this->filterCustomerRentDetails($customerData);

        $sessionCustomerRent = $this->filterCustomerRent($customerData);

        // customer details table
        $customerDetails = $this->createCustomerDetail($sessionCustomerDetails);
        Log::info("Successfully added!", [
            'customer_details' => $customerDetails,
        ]);
        // rent details table
        $customerRentDetails = $this->createRentDetail($sessionCustomerRentDetails);
        Log::info("Successfully added!", [
            'customer_rent_details' => $customerRentDetails,
        ]);
        // customer rent table
        $customerRents = $this->handleCustomerRent($sessionCustomerRent, [
            'customer_details_id' => $customerDetails['id']
        ]);
        Log::info("Successfully added!", [
            'customer_rent' => $customerRents,
        ]);

        return ['customerDetails' => $customerDetails, 'customerRent' => $customerRents, 'customerRentDetails' => $customerRentDetails];
    }

    private function filterCustomerDetail($customerData)
    {
        return [
            'name' => $customerData['name'],
            'contact' => $customerData['contact'],
            'address' => $customerData['address'],
            'email' => $customerData['email'] ?? null,
        ];
    }

    private function filterCustomerRentDetails($customerData)
    {
        return [
            'venue' => $customerData['venue'],
            'event_date' => $customerData['event_date'],
            'reason_for_renting' => $customerData['reason_for_renting'],
        ];
    }

    private function filterCustomerRent($customerData)
    {
        return [
            'pickup_date' => $customerData['pickup_date'],
            'rented_date' => $customerData['rented_date'],
            'return_date' => $customerData['return_date'],
        ];
    }

    /**
     * Request rent detail creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array rent detail data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    protected function createRentDetail($customerRentDetails)
    {
        return $this->handleRentDetail($customerRentDetails);
    }

    /**
     * Create a new customer detail.
     *
     * @param \Illuminate\Http\Request $request
     * @return array customer detail data
     */
    protected function createCustomerDetail($customerDetails)
    {
        // Handle the customer detail creation
        return $this->handleCustomerDetail($customerDetails);
    }

    /**
     * Handle the customer detail creation.
     *
     * @param array $customerData
     * @return \App\Models\Transactions\CustomerDetail
     */
    private function handleCustomerDetail(array $data): CustomerDetail
    {
        return CustomerDetail::create([
            'name' => $data['name'],
            'contact' => $data['contact'],
            'address' => $data['address'],
            'email' => $data['email'] ?? 'none',
        ]);
    }

    /**
     * Handle the customer rent creation.
     *
     * @param array $data
     * @param int $relation id
     * @return \App\Models\Transactions\CustomerDetail
     */
    private function handleCustomerRent(array $data, $relation): CustomerRent
    {
        return CustomerRent::create([
            'customer_details_id' => $relation['customer_details_id'],
            'pickup_date' => $data['pickup_date'],
            'rented_date' => $data['rented_date'],
            'return_date' => $data['return_date'],
        ]);
    }

    /**
     * Handle the rent detail creation.
     *
     * @param array $data
     * @return \App\Models\Transactions\CustomerRent
     */
    private function handleRentDetail(array $data): RentDetails
    {
        return RentDetails::create([
            'venue' => $data['venue'],
            'event_date' => $data['event_date'],
            'reason_for_renting' => $data['reason_for_renting'],
        ]);
    }
}
