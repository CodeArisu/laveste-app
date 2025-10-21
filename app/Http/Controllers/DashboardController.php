<?php

namespace App\Http\Controllers;

use App\Enum\Discounts;
use App\Models\Catalog;
use App\Models\Discount;
use App\Models\Products\Product;
use App\Models\Transactions\ProductRent;
use App\Models\Transactions\Transaction;
use App\Traits\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController
{
    use RandomStringGenerator;

    public function index()
    {
        $product = Product::all();
        $catalog = Catalog::all();
        $discounts = Discounts::cases();
        $codes = Discount::all();

        return view(
            'src.dashboard.index',
            [
                'productCount' => count($product),
                'catalogCount' => count($catalog),
                'discounts' => $discounts,
                'codes' => $codes,
            ]
        );
    }

    public function generate()
    {
        $generatedCode = $this->generateString();

        return redirect()->back()->with(['generatedCode' => $generatedCode]);
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'code' => 'string|unique:discounts,code|max:8',
            'coupon_type' => 'required|string',
            'description' => 'nullable|string',
        ])->validate();

        Discount::create([
            'code' => $validated['code'],
            'coupon_type' => $validated['coupon_type'],
            'description' => $validated['description'] ?? null,
            'starting_date' => now(),
            'expiry_date' => now()->addDays(3),
        ]);

        return redirect()->back()->with('success', 'Discount code successfully generated!');
    }

    public function transactions()
    {
        $transactions = Transaction::with('paymentMethod', 'productRent')->get();

        return view('src.dashboard.pages.transactions', ['transactions' => $transactions]);
    }

    public function rented()
    {

        $productRented = ProductRent::with('customerRent', 'rentDetail', 'catalog', 'productRentedStatus')->get();

        return view('src.dashboard.pages.rents', ['productRents' => $productRented]);
    }
}
