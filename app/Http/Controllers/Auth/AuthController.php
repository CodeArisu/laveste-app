<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{   
    public function __construct(protected AuthService $authService){}

    public function registerUser(AuthRequest $request)
    {   
        $user = $this->authService->registerRequest($request);
        return $this->authService->userResponse(["token" => $user['token'], 'message' => $user['message']]);
    }
        
    public function loginUser(AuthRequest $request) 
    {    
        $user = $this->authService->loginRequest($request);
        return $this->authService->userResponse(["token" => $user['token'], 'message' => $user['message']]);
    }

    public function logoutUser(Request $request) : JsonResponse
    {   
        $user = $this->authService->logoutRequest($request);
        return $this->authService->userResponse(["token" => "", 'message' => $user['message']]);
    }
}