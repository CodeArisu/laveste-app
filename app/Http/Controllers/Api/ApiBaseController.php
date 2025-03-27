<?php

namespace App\Http\Controllers\api;

use App\Enum\StatusCode;

use Illuminate\Http\JsonResponse;

abstract class ApiBaseController    
{   
    protected function sendError($message) : JsonResponse
    {
        return response()->json([
            'error' => $message,
        ], StatusCode::ERROR->value);
    }

    protected function sendSuccess($message) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => $message,
        ], StatusCode::SUCCESS->value);
    }

    public function isChecked($object, $message) : void
    {
        if ($object === false || $object === null) {
            abort(StatusCode::ERROR->value, $message);
        }
    }
}