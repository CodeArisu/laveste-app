<?php

namespace App\Traits;

use App\Enum\ResponseCode;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponsesTrait
{
    protected function sendResponse($message, $data) : JsonResponse
    {   
        if (empty($data)) {
            return response()->json([
                'Failed' => $message,
                'Response' => ResponseCode::ERROR->value
            ], ResponseCode::ERROR->value);
        }

        return response()->json([
            'Success' => $message,
            'Response' => ResponseCode::OK->value
        ], ResponseCode::OK->value);
    }

    protected function successResponse($message, $data) : array 
    {   
        Log::info($message);
        return ['message' => $message, 'data' => $data];
    }

    protected function successDeleteResponse($message, $isDeleted = true)
    {   
        Log::info($message);
        return ['message' => $message, 'deleted' => $isDeleted];
    }
}
