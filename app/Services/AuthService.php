<?php

namespace App\Services;

use App\Enum\{StatusCode, UserRoles};
use App\Exceptions\InvalidUserException;
use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\AuthRequest;
use App\Models\{Role as ModelsRole, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Auth, Hash};

class AuthService extends ApiBaseController
{      
    /**
     * @param AuthRequest $request
     * @return array
     */
    public function registerRequest(AuthRequest $request)
    {   
        try {
            return DB::transaction(function () use ($request) {
                $user =  $this->registerUser($request);
                return ['token' => $user['authToken'], 'message' => 'User registered'];
            });
        } catch (\Exception $e) {
            return $this->exceptionResponse($e, 'Failed to register user');
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function loginRequest(AuthRequest $request)
    {   
        try {
            $user = $this->loginUser($request);
            return ['token' => $user['authToken'], 'message' => 'User signed in'];
        } catch (\Exception $e) {
            return $this->exceptionResponse($e, 'Failed to login user');
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function logoutRequest($request)
    {   
        try {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
                return ['token' => null, 'message' => 'User signed out'];
            }
            return ['token' => null, 'message' => 'Try again later'];
        } catch (\Exception $e) {
            return $this->exceptionResponse($e, 'Failed to logout user');
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    private function registerUser($request)
    {   
        if (!ModelsRole::exists()) {
            $this->registerRoles();
        }
       
        $user = $this->handleRegister($request->validated());

        // checks if the user already exists
        // function for handling user exists
    
        $authToken = $user->createToken('auth_token')->plainTextToken;

        return compact('user', 'authToken');
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
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

    /**
     * @param Request creates new roles enums exists
     */
    private function loginUser($request)
    {
        if (!Auth::attempt($request->safe()->only('email', 'password'), $request->boolean('remember'))) {
            throw InvalidUserException::InvalidUserCredentials('Invalid user credentials');
            // send message
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

    /**
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function userResponse(array $params)
    {
        return response()->json([
            'message' => $params['message'],
            'access_token' => $params['token'] ?? null,
            'token_type' => !empty($params['token']) ? 'bearer' : 'revoked'
        ], StatusCode::SUCCESS->value);
    }
}