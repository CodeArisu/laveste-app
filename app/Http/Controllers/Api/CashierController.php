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
        protected ProductRentService $productRentService,
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
        $productReceive = $this->productRentService->updateProductRent($productRent);

        return redirect()->back()->with('success', $productReceive['message']);
    }

    public function appointmentCompleted(Appointment $appointment)
    {   
        $this->productRentService->updateToCompleted($appointment);

        return redirect()->back()->with('success', 'Updated to complete');
    }

     public function appointmentCancelled(Appointment $appointment)
    {
        $this->productRentService->updateToCancelled($appointment);

        return redirect()->back()->with('success', 'Updated to cancelled');
    }
}
