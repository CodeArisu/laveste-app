<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Products\Product;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        $product = Product::all();
        $catalog = Catalog::all();
        return view('src.admin.dashboard', ['productCount' => count($product), 'catalogCount' => count($catalog)]);
    }

    public function transactions()
    {
        $transactions = Transaction::with('paymentMethod', 'productRent')->get();

        return view('src.admin.transactions', ['transactions' => $transactions]);
    }

    public function rented()
    {
        $productRented = ProductRent::with('customerRent', 'rentDetail', 'catalog', 'productRentedStatus')->get();

        return view('src.admin.prodrented', ['productRents' => $productRented]);
    }
}
