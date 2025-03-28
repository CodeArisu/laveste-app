<?php

namespace App\Services;

use App\Enum\{StatusCode, UserRoles};
use App\Exceptions\InternalException;
use App\Http\Requests\AuthRequest;
use App\Models\{Role as ModelsRole, User};
use Illuminate\Support\Facades\{Auth, Hash};

class AuthService
{   
    public function registerRequest(AuthRequest $request)
    {   
        if (!$request) {
            throw InternalException::failedRequest();
        }
        $user =  $this->registerUser($request->validated());
        return $user;
    }

    public function loginRequest(AuthRequest $request)
    {   
        if (!$request) {
            throw InternalException::failedRequest();
        }
        $user = $this->loginUser($request);
        return $user;
    }

    private function registerUser($request)
    {   
        $this->registerRoles();

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
            ], 401);
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
            'access_token' => $params['token'],
            'token_type' => empty($params['token']) ? 'bearer' : 'revoked'
        ], StatusCode::SUCCESS->value);
    }
}