<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\CustomerDetailsRequest;
use App\Models\Catalog;
use App\Services\ProductRentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductRentController extends ApiBaseController
{
    public function __construct(protected ProductRentService $productRentService) {}

    public function index(Catalog $catalogs)
    {   
        return view('src.cashier.checkout', ['catalogs' => $catalogs]);
    }

    public function store(CustomerDetailsRequest $request, Catalog $catalogs)
    {   
       $this->productRentService->requestProductRent($request);

        if (!Session::has('checkout.customer_data')) {
            // return exception
            Throw new \Exception('Session doesn\'t exists');
        }

        return redirect()->route('cashier.checkout', ['catalogs' => $catalogs]);
    }
}
