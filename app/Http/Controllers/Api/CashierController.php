<?php

namespace App\Http\Controllers\Api;

use App\Models\Transactions\Appointment;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use App\Services\ProductRentService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class CashierController
{   
    public function __construct(
        protected TransactionService $transactionService, 
        protected ProductRentService $productRentService
    ){}

    public function rentalsIndex() 
    {   
        $productRents = ProductRent::with(['catalog.garment', 'customerRent.customerDetail'])->get();
        $customerAppointments = Appointment::with(['customerDetail', 'appointmentStatus'])->get();

        return view('src.cashier.home', [
            'productRents' => $productRents,
            'appointments' => $customerAppointments,
        ]);
    }

    public function productRentUpdate(ProductRent $productRent)
    {  
        $this->productRentService->updateProductRent($productRent);
    }
}
