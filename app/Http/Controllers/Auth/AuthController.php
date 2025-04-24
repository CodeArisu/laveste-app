<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiBaseController
{   
    public function __construct(protected AuthService $authService){}

    public function registerUser(AuthRequest $request)
    {   
        $user = $this->authService->registerRequest($request);
        return $this->authService->userResponse([
            'message' => $user['message'], 
        ]);
    }

    public function registerIndex()
    {
        return view('src.admin.users.register');
    }
        
    public function loginUser(AuthRequest $request) 
    {    
        $user = $this->authService->loginRequest($request);
        return redirect()->route($user['url'])->with($user['message']);
    }

    public function loginIndex()
    {
        return view('src.login');
    }

    public function logoutUser(Request $request) : JsonResponse
    {   
        $user = $this->authService->logoutRequest($request);
        return $this->authService->userResponse([
            "message" => $user['message']
        ]);
    }
}