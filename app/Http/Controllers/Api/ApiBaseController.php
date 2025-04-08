<?php

namespace App\Http\Controllers\api;

use App\Enum\StatusCode;
use Illuminate\Http\JsonResponse;

abstract class ApiBaseController    
{    
    protected function sendResponse($message, $data) : JsonResponse
    {   
        // checks if the data is not empty returns true
        $data = !empty($data) ?? false;
        if ($data !== true) {
            return response()->json([
                'Failed' => $message,
            ], StatusCode::ERROR->value);
        }

        return response()->json([
            'Success' => $message,
        ], StatusCode::SUCCESS->value);
    }
}