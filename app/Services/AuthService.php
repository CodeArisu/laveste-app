<?php

namespace App\Services;

use App\Enum\{StatusCode, UserRoles};
use App\Exceptions\InternalException;
use App\Http\Requests\AuthRequest;
use App\Models\{Role as ModelsRole, User};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\{Auth, Hash};

class AuthService
{   
    public function registerRequest(AuthRequest $request)
    {   
        try {
            $user =  $this->registerUser($request->validated());
            return ['token' => $user['authToken'], 'message' => 'User signed up'];
        } catch (\Exception $e) {
            Log::error("User registration failed: " . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function loginRequest(AuthRequest $request)
    {   
        try {
            $user = $this->loginUser($request);
            return ['token' => $user['authToken'], 'message' => 'User signed in'];
        } catch (\Exception $e) {
            Log::error("User authentication failed: " . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function logoutRequest($request)
    {   
        try {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
                return ['token' => null, 'message' => 'User signed out'];
            }
            return ['token' => null, 'message' => 'Try again later'];
        } catch (\Exception $e) {
            Log::error("User logout failed: " . $e->getMessage());
            throw new InternalException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function registerUser($request)
    {   
        if (!ModelsRole::exists()) {
            $this->registerRoles();
        }
       
        $user = $this->handleRegister($request);
        $authToken = $user->createToken('auth_token')->plainTextToken;

        return compact('user', 'authToken');
    }

    private function handleRegister($request)
    {   
        return User::firstOrCreate([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => UserRoles::MANAGER->value,
            'user_details_id' => $request['user_details_id'] ?? null
        ]);
    }

    private function loginUser($request)
    {
        if (!Auth::attempt($request->safe()->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], StatusCode::UNAUTHORIZED->value);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $authToken = $user->createToken('auth_token')->plainTextToken;

        return compact('user', 'authToken');
    }

    /**
     * @param void creates new roles enums exists
     */
    private function registerRoles() : void
    {   
        $existingRoles = ModelsRole::pluck('role_name')->toArray();
        $allRoles = array_map( fn($role) => $role->label(), UserRoles::cases());

        if (count(array_diff($allRoles, $existingRoles))) {
            foreach (UserRoles::cases() as $role) {
                ModelsRole::firstOrCreate(['role_name' => $role->label()]);
            }
        }
    }

    public function userResponse(array $params)
    {
        return response()->json([
            'message' => $params['message'],
            'access_token' => $params['token'] ?? null,
            'token_type' => !empty($params['token']) ? 'bearer' : 'revoked'
        ], StatusCode::SUCCESS->value);
    }
}