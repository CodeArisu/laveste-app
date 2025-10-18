<?php

namespace App\Services;

use App\Enum\ResponseCode;
use App\Exceptions\AuthException;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Auth\User;
use Exception;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthService extends UserRepository
{
    public function __construct(User $model) {
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
                $user = $this->registerUser($validated);

                return $this->userRedirect($user);
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
    public function loginRequest($request)
    {
        try {
            // validates the incoming request
            $validated = $request->validated();

            // function from AuthTraits to login user
            $user = $this->loginUser($validated);

            return $this->userRedirect($user);
        } catch (AuthException $e) {
            // Re-throw Auth-specific exceptions (invalid credentials, locked account, etc.)
            throw $e;
            throw AuthException::userNotFound();
        } catch (Exception $e) {
            throw AuthException::userLoginFailed();
        }
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function logoutRequest($request)
    {
        $user = $request->user();

        try {
            $user = $this->logoutUser($user);

            return $user;
        } catch (AuthException $e) {
            // Re-throw pre-formatted auth exceptions
            throw $e;
        } catch (\Throwable $e) {
            // Log unexpected errors
            throw AuthException::logoutFailed('Could not complete logout');
        }
    }

    /**
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function userResponse(array $params): JsonResponse
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
