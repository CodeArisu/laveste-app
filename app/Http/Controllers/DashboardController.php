<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Products\Product;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use App\Traits\RandomStringGenerator;
use Illuminate\Http\Request;

class DashboardController
{   
    use RandomStringGenerator;

    public function index()
    {   
        $product = Product::all();
        $catalog = Catalog::all();
        
        return view('src.admin.dashboard', 
        [
            'productCount' => count($product), 
            'catalogCount' => count($catalog),
        ]);
    }

    public function generate() {
        $generatedCode = $this->generateString();
        return redirect()->back()->with(['generatedCode' => $generatedCode]);
    }

    public function register()
    {
        return redirect()->back();
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
