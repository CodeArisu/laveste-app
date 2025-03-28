<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{   
    public function registerUser(AuthRequest $request, AuthService $authService)
    {   
        $user = $authService->registerRequest($request);
        return $authService->userResponse(['token' => $user['authToken'], 'message' => 'Registration Successful']);
    }
        
    public function loginUser(AuthRequest $request, AuthService $authService) 
    {    
        $user = $authService->loginRequest($request);
        return $authService->userResponse(['token' => $user['authToken'], 'message' => 'Login Successful']);
    }

    public function logoutUser(Request $request, AuthService $authService) : JsonResponse
    {   
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return $authService->userResponse(['token' => null, 'message' => 'Logout Successful']);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
}