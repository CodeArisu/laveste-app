<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\CustomerDetailsRequest;
use App\Services\ProductRentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductRentController extends ApiBaseController
{
    public function __construct(protected ProductRentService $productRentService) {}

    public function index()
    {
        return view('src.cashier.checkout');
    }

    public function show()
    {   
        $customerData = Session::get('checkout.customer_data');
        $transactionData = Session::get('checkout.transaction_data');
        
        return response()->json([
            'customer_data' => $customerData,
            'transaction_data' => $transactionData,
            'process_step' => 1,
        ]);
    }

    public function store(CustomerDetailsRequest $request) 
    {
       $productRent = $this->productRentService->requestProductRent($request);
        if (!Session::has('checkout.customer_data')) {
            // return exception
            dd('Session does not exist');
        }

        return redirect()->route('cashier.details.show');
    }
}
