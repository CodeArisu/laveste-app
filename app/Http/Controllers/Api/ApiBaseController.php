<?php

namespace App\Http\Controllers\api;

use App\Enum\StatusCode;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

abstract class ApiBaseController    
{   
    protected function sendCreateResponse($message, $data) : JsonResponse
    {   
        return response()->json([
            'success' => !empty($data) ?? false,
            'response' => $message,
        ], StatusCode::SUCCESS->value);
    }

    protected function sendUpdateResponse($message, $data, $newFields) : JsonResponse
    {   
        return response()->json([
            'success' => !empty($data) ?? false,
            'updated_data' => $newFields ?? null,
            'response' => $message,
        ], StatusCode::SUCCESS->value);
    }

    protected function sendDeleteResponse($message, $isDeleted, $productName) : JsonResponse
    {   
        return response()->json([
            'success' => $isDeleted,
            'data_name' => $productName ?? null,
            'response' => $message,
        ], StatusCode::SUCCESS->value);
    }

}