<?php

namespace App\Services;

use App\Enum\UserRoles;
use App\Models\Role as ModelsRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{   
    public function registerUser($request)
    {   
        $this->registerRoles();

        $user = $this->handleRegister($request);
        $authToken = $user->createToken('auth_token')->plainTextToken;

        return compact('user', 'authToken');
    }

    public function handleRegister($request)
    {   
        return User::firstOrCreate([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => UserRoles::MANAGER->value,
            'user_details_id' => $request['user_details_id'] ?? null
        ]);
    }

    public function loginUser($request)
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
}