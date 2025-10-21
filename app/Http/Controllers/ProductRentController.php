<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerDetailsRequest;
use App\Models\Catalog;
use App\Services\ProductRentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductRentController
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
            throw new \Exception('Session doesn\'t exists');
        }

        return redirect()->route('cashier.checkout', ['catalogs' => $catalogs]);
    }
}
