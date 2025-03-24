<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\ApiBaseController;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiBaseController
{
    public function index()
    {
        return response()->json([
                'success' => 'allowed',
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'product_name' => 'required|string|max:50',
            'original_price' => 'required|integer',
            'description' => 'nullable|max:255',

            'supplier_name' => 'nullable|string|regex:/^\d{11}$/|max:65',
            'company_name' => 'nullable|string|max:65',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|regex:/^\d{11}$/|unique:suppliers,contact'
        ]);

        $this->validationFailed($validated);

        DB::beginTransaction();
        try {

            // new supplier
            $supplier = $this->storeSupplier(
                $request->only(
                    'supplier_name',
                    'company_name',
                    'address',
                    'contact'
                )
            );

            // new product type
            $productType = $this->productType(
                $request->only(
                    'type',
                    'subtype'
                )
            );

            // new product added
            $product = $this->storeProduct(
                $request->only(
                    'product_name',
                    'original_price',
                    'description'
                ), [ 
                    'supplier' => $supplier,
                    'productType' => $productType
                ]
            );

            DB::commit();
            return $this->sendSuccess('Success', $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e);
        }
    }

    public function show(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }
}
