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
                $this->registerUser($request);
                return [
                    'message' => 'User registered',
                    'url' => ''
                ];
            });
        } catch (QueryException $e) {
            throw AuthException::userRegistrationFailed();
        } catch (ModelNotFoundException $e) {
            throw AuthException::userRegistrationFailed();
        } catch (\Throwable $e) {
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
            $this->loginUser($request);
            return [
                'message' => 'Login Successful',
                'url' => 'dashboard.home'
            ];
        }
        catch (AuthException $e) {
            // Re-throw Auth-specific exceptions (invalid credentials, locked account, etc.)
            throw $e;
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

            Auth::logout();

            return [
                'message' => 'User signed out',
                'url' => 'login'
            ];

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

        $this->checkIfExists($request);
        $this->handleRegister($request->validated());
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
    private function loginUser(AuthRequest $request)
    {   
            $authenticated = $request->authenticate();
            $request = $request->safe();
            
            $user = User::where('email', $request['email'])->first();
            if (!$authenticated || !$user || !Hash::check($request['password'], $user->password))
            {
                throw AuthException::invalidUserCredentials();
            }

            return compact('user');
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