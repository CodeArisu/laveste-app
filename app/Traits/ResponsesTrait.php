<?php

namespace App\Traits;

use App\Enum\ResponseCode;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponsesTrait
{
    /**
     * @param array $response
     * @return \Illuminate\Http\RedirectResponse
     *
     * for web type response
     */
    protected function getResponse($response)
    {
        if ($response['error']) {
            return redirect()->to($response['next'])->with('error', $response['message']);
        }

        return redirect()->to($response['next'])->with('success', $response['message']);
    }

    /**
     * @param array $response
     * @return JsonResponse
     *
     * for api type response
     */
    protected function getJsonResponse($response): JsonResponse
    {
        if ($response['error']) {
            Log::error('Response Error: ' . $response['message']);
            return response()->json([
                'status' => 401,
                'message' => $response['message'],
                'body' => $response['data']
            ], 401);
        }

        return response()->json([
            'status' => 200,
            'message' => $response['message'],
            'body' => $response['data']
        ], 200);
    }
}
