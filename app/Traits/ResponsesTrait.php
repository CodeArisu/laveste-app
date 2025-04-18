<?php

namespace App\Traits;

use App\Enum\ResponseCode;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponsesTrait
{   
    protected function sendResponse(
        $data,
        string $message
        ) : JsonResponse
    {   
        if (empty($data)) {
            return response()->json([
                'status' => 'failed',
                'data' => [
                    'message' => $message ?? $this->message,
                    'code' => ResponseCode::ERROR->value
                ] 
            ], ResponseCode::ERROR->value);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'data' => $data['product']->id ?? null,
                'message' => $message,
                'Response' => ResponseCode::OK->value
            ],
        ], ResponseCode::OK->value);
    }
}