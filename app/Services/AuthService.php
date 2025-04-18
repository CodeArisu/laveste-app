<?php

namespace App\Services;

use App\Enum\ResponseCode;
use App\Enum\UserRoles;
use App\Exceptions\AuthException;
use App\Http\Requests\AuthRequest;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\{Auth, Hash};

class AuthService
{
    /**
     * @param AuthRequest $request
     * @return array
     */
    public function registerRequest(AuthRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = $this->registerUser($request);
                return [
                    'token' => $user['authToken'], 
                    'message' => 'User registered'
                ];
            });
        } catch (QueryException $e) {
            // Database errors (e.g., unique constraint violations)
            report($e); // Log to error tracking system
            throw AuthException::userRegistrationFailed();
        } catch (ModelNotFoundException $e) {
            // Missing required relations (if your registration has dependencies)
            throw AuthException::userRegistrationFailed();
        } catch (\Throwable $e) {
            // Catch-all for unexpected errors
            report($e);
            throw AuthException::userRegistrationFailed();
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
            return [
                'token' => $user['authToken'], 
                'message' => 'User signed in'
            ];
        }
        catch (AuthException $e) {
            // Re-throw Auth-specific exceptions (invalid credentials, locked account, etc.)
            throw $e;
        } catch (ModelNotFoundException $e) {
            // User not found (though loginUser should prevent this)
            throw AuthException::userNotFound();
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function logoutRequest($request)
    {
        try {
            if (!$request->user()) {
                throw AuthException::unauthenticated('No authenticated user found');
            }
            $request->user()->currentAccessToken()->delete();
            return ['token' => null, 'message' => 'User signed out'];
        } catch (AuthException $e) {
            // Re-throw pre-formatted auth exceptions
            throw $e;
        } catch (\Throwable $e) {
            // Log unexpected errors
            report($e);
            throw AuthException::logoutFailed('Could not complete logout');
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    private function registerUser($request)
    {
        if (!Role::exists()) {
            $this->registerRoles();
        }

        if (User::where('email', $request->safe()->only(['email']))->exists()) {
            throw AuthException::userEmailAlreadyTaken();
        }

        $this->checkIfExists($request);

        $user = $this->handleRegister($request->validated());

        $authToken = $user->createToken('auth_token')->plainTextToken;

        return compact('user', 'authToken');
    }

    /**
     * @param Request $request
     */
    private function checkIfExists($request)
    {
        $exist = User::where('email', $request->email)
        ->orWhere('name', $request->name)
        ->exists();

        if ($exist) {
            throw AuthException::userAlreadyRegistered();
        }
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
            'user_details_id' => $request['user_details_id'] ?? null,
        ]);
    }

    /**
     * @param Request creates new roles enums exists
     */
    private function loginUser($request)
    {   
        if (!Auth::attempt($request->safe()->only(['email', 'password']))) {
            Log::warning('Invalid Credentials');
            throw AuthException::invalidUserCredentials();
        }
        
        $user = User::where('email', $request->email)->firstOrFail();
        $authToken = $user->createToken('auth_token', ['*'], now()->addDays(7))->plainTextToken;

        return compact('user', 'authToken');
    }

    /**
     * @param void creates new roles enums exists
     */
    private function registerRoles(): void
    {
        $existingRoles = Role::pluck('role_name')->toArray();
        $allRoles = array_map(fn($role) => $role->label(), UserRoles::cases());

        if (count(array_diff($allRoles, $existingRoles))) {
            foreach (UserRoles::cases() as $role) {
                Role::firstOrCreate(['role_name' => $role->label()]);
            }
        }
    }

    /**
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function userResponse(array $params) : JsonResponse
    {   
        Log::info('Status: Success \n' .  $params['message']);
        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => $params['message'],
                'access_token' => $params['token'] ?? null,
                'token_type' => !empty($params['token']) ? 'bearer' : 'revoked',
            ],
        ], ResponseCode::OK->value);
    }
}