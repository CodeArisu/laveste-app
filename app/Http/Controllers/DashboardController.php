<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        $product = Product::all();
        $catalog = Catalog::all();
        return view('src.admin.dashboard', ['productCount' => count($product), 'catalogCount' => count($catalog)]);
    }
}
