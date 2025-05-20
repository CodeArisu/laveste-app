<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Services\CustomerDetailService;
use Illuminate\Http\Request;


class CustomerDetailsController
{
    public function __construct(protected CustomerDetailService $customerDetailService){}

    public function store(Request $request)
    {

    }
}
