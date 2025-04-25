<?php

namespace App\Services;

use App\Exceptions\InternalException;
use App\Http\Requests\CustomerDetailsRequest;
use App\Models\Transactions\CustomerDetail;
use App\Models\Transactions\CustomerRent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerDetailService
{
    public function __construct(){}

    /**
     * Request customer detail creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array customer detail data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    public function requestCustomerDetail(CustomerDetailsRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $customerDetails = $this->createCustomerRent($request);
                if (empty($customerDetails)) {
                    throw new \RuntimeException('No customer details were added');
                }
                return ['customer_details' => $customerDetails, 'message' => 'Customer details added successfully'];
            });
        } catch (\Exception $e) {
           
        }
    }

    /**
     * Request customer rent creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array customer rent data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    protected function createCustomerRent(CustomerDetailsRequest $request)
    {
        $validated = $request->safe();

        $customerDetails = $this->createCustomerDetail($validated);

        $customerRent = $this->handleCustomerRent($validated->only([
            'pickup_date',
            'rented_date',
            'return_date',
        ]), [
            'customer_details_id' => $customerDetails['customer_details']->id,
        ]);

        $customerRentDetails = $this->createRentDetail($validated);
        
        return compact('customerDetails', 'customerRent', 'customerRentDetails');
    }

    /**
     * Request rent detail creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return array rent detail data and message response
     * @throws \Exception RuntimeException / InternalException
     */
    protected function createRentDetail($validated)
    {
        $customerDetails = $this->handleRentDetail($validated->only([
            'venue',
            'event_date',
            'reason_for_renting',
        ]));

        return compact('customerDetails');
    }

    /**
     * Create a new customer detail.
     *
     * @param \Illuminate\Http\Request $request
     * @return array customer detail data
     */
    protected function createCustomerDetail($validated)
    {
        // Handle the customer detail creation
        $customerDetails = $this->handleCustomerDetail($validated->only([
            'name',
            'contact',
            'address',
            'email',
        ]));

        return compact('customerDetails');
    }

    /**
     * Handle the customer detail creation.
     *
     * @param array $customerData
     * @return \App\Models\Transactions\CustomerDetail
     */
   private function handleCustomerDetail(array $data)
   {
        return CustomerDetail::create([
            'name' => $data['name'],
            'contact' => $data['contact'],
            'address' => $data['address'],
            'email' => $data['email'],
        ]);
   }

    /**
     * Handle the customer rent creation.
     *
     * @param array $data
     * @param int $relation id
     * @return \App\Models\Transactions\CustomerDetail
     */
   private function handleCustomerRent(array $data, $relation) : CustomerDetail
   {
        return CustomerDetail::create([
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
   private function handleRentDetail(array $data) : CustomerRent
   {
        return CustomerRent::create([
            'venue' => $data['venue'],
            'event_date' => $data['event_date'],
            'reason_for_renting' => $data['reason_for_renting'],
        ]);
   }
}