<?php

namespace App\Services;

use App\Models\Auth\{User, UserDetail};
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService extends UserRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        User $model,
        UserDetail $userDetail
    ) {
        parent::__construct($model);
    }

    /**
     * Retrieves all user data
     */
    public function getUser()
    {
        return $this->model->all();
    }

    /**
     * Stores user details
     */
    public function storeRequest($request, $user)
    {
        try {
            return DB::transaction(function () use ($request, $user) {
                // validates the incoming request
                $validated = $request->validated();

                // function from AuthTraits to create user details
                $userDetails = $this->createUserDetails($user, $validated);

                return [
                    'error' => false,
                    'message' => 'User details added successfully',
                    'next' => redirect()->back(),
                    'data' => [
                        'userDetails' => $userDetails,
                    ]
                ];
            });
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Error: ' . $e,
                'next' => redirect()->back(),
                'data' => [
                    'userDetails' => [],
                ]
            ];
        }
    }

    /**
     * Updates user details
     */
    public function updateRequest($request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // validates the incoming request
                $userId = $request->user()->id;
                $validated = $request->validated();

                // function from AuthTraits to update user
                $user = $this->updateUser($userId, $validated);

                return [
                    'error' => false,
                    'message' => 'User details updated successfully',
                    'next' => redirect()->back(),
                    'data' => [
                        'user' => $user,
                    ]
                ];
            });
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Error: ' . $e,
                'next' => redirect()->back(),
                'data' => [
                    'user' => [],
                ]
            ];
        }
    }

    public function disableRequest($id)
    {
        // finds and updates user
        $user = $this->model->update([
            'disabled_at' => now(),
            'updated_at'  => now()
        ]);

        if (!$user) {
            throw new Exception("User not found.");
        }

        return [
            'error' => false,
            'message' => 'User has been disabled.',
            'next' => redirect()->back(),
            'data' => [
                'user' => $user,
            ]
        ];
    }
}
