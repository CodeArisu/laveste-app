<?php

namespace App\Services;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Auth\User;
use Exception;
use App\Repositories\UserRepository;

class AuthService extends UserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function registerRequest($request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // validates the incoming request
                $validated = $request->validated();
                // function from AuthTraits to register user
                $user = $this->register($validated);

                return [
                    'error' => false,
                    'message' => 'User registered successfully',
                    'next' => $this->userRedirect($user),
                    'data' => [
                        'user' => $user,
                    ]
                ];
            });
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'next' => null,
                'data' => []
            ];
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function loginRequest($request)
    {
        try {
            // validates the incoming request
            $validated = $request->validated();
            // function from AuthTraits to login user
            $user = $this->authenticate($validated);

            return [
                'error' => false,
                'message' => 'User logged in successfully',
                'next' => $this->userRedirect($user),
                'data' => [
                    'user' => $user,
                ]
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'next' => null,
                'data' => []
            ];
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function logoutRequest($request)
    {
        // set request user
        $user = $request->user();
        try {
            $user = $this->logout($user);
            return [
                'error' => false,
                'message' => 'User logged out successfully',
                'next' => $this->userRedirect($user),
                'data' => []
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'next' => null,
                'body' => []
            ];
        }
    }
}
