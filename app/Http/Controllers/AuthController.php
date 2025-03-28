<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{   
    public function registerUser(AuthRequest $request, AuthService $authService)
    {   
        try {
            $userData = $authService->registerUser($request->validated());
            
            return $this->userResponse(
                $userData['authToken'], 
                $userData['user'],
                'Registration Successful'
            );
        } catch (\Exception $e) {
            return $this->sendError('Registration Failed', $e->getMessage(), 400);
        }
    }
        
    public function loginUser(AuthRequest $request, AuthService $authService) 
    {   
        try {
            $user = $authService->loginUser($request);

            return $this->userResponse(
                $user['authToken'], 
                $user['user'],
                'Login Successful'
            );
        } catch (\Exception $e) {
            $this->sendError('An Error has Occurred', $e->getMessage());
        }
    }

    public function logoutUser(Request $request)
    {   
        try {
            // revokes user access token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out Successfully'
            ], 200);
        } catch (\Exception $e) {
            return $this->sendError('An Error has Occurred', $e->getMessage());
        }
        
    }

    private function userResponse($authToken, $user, $message)
    {
        return response()->json([
            'message' => $message,
            'access_token' => $authToken,
            'type' => $user->role->id,
            'token_type' => 'Bearer'
        ], 201);
    }

    private function sendError($message, $error)
    {
        return response()->json([
            'message' => $message,
            'error' => $error
        ], 403);
    }
}