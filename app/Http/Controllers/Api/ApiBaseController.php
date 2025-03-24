<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Subtype;
use App\Models\Supplier;
use App\Models\Type;

use Illuminate\Http\JsonResponse;

abstract class ApiBaseController extends Controller
{      
    protected function validationFailed($validated, $statusCode = 422) : JsonResponse
    {
        return $validated->fails() ? response()
        ->json(['errors' => $validated->errors()], $statusCode) : response()
        ->json(['error' => 'null']);
    }

    protected function sendError($message, $statusCode = 400) : JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], $statusCode);
    }

    protected function sendSuccess($message, $data, $statusCode = 202) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * @param storeProduct $validated data, $offsetData data such as id's
     */
    protected function storeProduct($productData, $offsetData)
    {   
        return Product::firstOrCreate([
            'product_name' => $productData['product_name'],
            'original_price' => $productData['original_price'],
            'description' => $productData['description'], 
            'supplier_id' => $offsetData['supplier']['id'],
            'product_type_id' => $offsetData['productType']['id'],
        ]);
    }

    protected function storeSupplier($supplierData)
    {   
        return Supplier::create([
            'supplier_name' => $supplierData['supplier_name'],
            'company_name' => $supplierData['company_name'],
            'address' => $supplierData['address'],
            'contact' => $supplierData['contact']
        ]);
    }

    protected function storeTypes($typeData)
    {   
        $mainType = Type::firstOrCreate([
            'type_name' => $typeData['type']
        ]);

        $subType = Subtype::firstOrCreate([
            'subtype_name' => $typeData['subtype']
        ]);

        if (!$mainType || !$subType) {
            $this->sendError('No Data Type or SubType ID Found!');
        }
        return [$mainType, $subType];
    }

    protected function storeProductType($typeID)
    {
        $productType = ProductType::create([
            'type_id' => $typeID['main'],
            'subtype_id' => $typeID['sub']
        ]);

        if (!$productType) {
            $this->sendError('No Data Product Type ID Found!');
        }

        return $productType;
    }

    protected function productType($typesData) 
    {   
        // new main clothing type and subtypes
        [$mainType, $subType] = $this->storeTypes($typesData);
        // new product type pivot
        return $this->storeProductType([
            'main' => $mainType->id,
            'sub' => $subType->id
        ]);
    }
}