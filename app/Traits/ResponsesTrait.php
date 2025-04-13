<?php

namespace App\Traits;

use App\Enum\StatusCode;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponsesTrait
{
    protected function sendResponse($message, $data) : JsonResponse
    {   
        if (empty($data)) {
            return response()->json([
                'Failed' => $message,
                'Response' => StatusCode::ERROR->value
            ], StatusCode::ERROR->value);
        }

        return response()->json([
            'Success' => $message,
            'Response' => StatusCode::SUCCESS->value
        ], StatusCode::SUCCESS->value);
    }

    /**
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function userResponse(array $params)
    {
        return response()->json([
            'message' => $params['message'],
            'access_token' => $params['token'] ?? null,
            'token_type' => !empty($params['token']) ? 'bearer' : 'revoked'
        ], StatusCode::SUCCESS->value);
    }
    public function userSuccessResponse($message, $user)
    {
        return ['token' => $user['authToken'], 'message' => $message];
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

    protected function exceptionResponse($e, $message) : JsonResponse
    {
        Log::error($message . "\n" . $e->getMessage());
        return response()->json([
            'Failed' => $message,
            'Error' => $e->getMessage()
        ], StatusCode::ERROR->value);
    }
}
