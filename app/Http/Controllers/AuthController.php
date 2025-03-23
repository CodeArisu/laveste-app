<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{   
    /**
     * @param void loops and creates through roles enums exists
     */
    private function registerRoles()
    {
        foreach (\app\Enums\Role::cases() as $roles) {
            \app\Models\Role::firstOrCreate(['role_name' => $roles->label()]);
        }
    }

    public function registerUser(Request $request)
    {   
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        $this->isValidated($validated);

        try {
            // creates user roles
            $this->registerRoles();
            // creates the new user with default role
            $user = \app\Models\User::firstOrCreate([$validated, 'role_id' => \app\Enums\Role::EMPLOYEE->value]);

            // creates new token
            $authToken = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $authToken,
                'user' => $user,
                'type' => $user->role,
                'token_type' => 'Bearer'
            ], 201);
        } catch (\Exception $e) 
        {
            return response()->json([
                'messages' => 'An Error has Occurred',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function loginUser(Request $request) 
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:8'
        ]);
        $this->isValidated($validated);

        try {
            // temporary allocation
            $credential = [
                'email' => $request->email,
                'password' => $request->password
            ];

            // checks if the credentials matched
            if (!auth()->attempt($credential))
            {
                return response()->json([
                    'message' => 'Invalid Credentials'
                ], 403);
            }

            // finds user email
            $user = \app\Models\User::where('email', $credential['email'])->firstOrFail();

            // creates new token
            $authToken = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $authToken,
                'user' => $user,
                'type' => $user->roles->role,
                'token_type' => 'Bearer'
            ], 201);
            
        } catch (\Exception $e)
        {
            return response()->json([
                'messages' => 'An Error has Occurred',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function logoutUser(Request $request)
    {   
        // revokes user access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out Successfully'
        ], 200);
    }
}
