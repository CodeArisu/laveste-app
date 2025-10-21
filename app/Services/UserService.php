<?php

namespace App\Services;

use App\Models\Auth\User;
use App\Repositories\UserRepository;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService extends UserRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUser()
    {
        return $this->model->all();
    }

    public function updateRequest($request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // validates the incoming request
                $userId = $request->user()->id;
                $validated = $request->validated();

                // function from AuthTraits to update user
                $user = $this->updateUser($userId, $validated);

                return $this->userRedirect($user);
            });
        } catch (QueryException $e) {
            // throw AuthException::userUpdateFailed();
        } catch (ModelNotFoundException $e) {
            // throw AuthException::userUpdateFailed();
        } catch (\Throwable $e) {
            // throw AuthException::userUpdateFailed();
        }
    }
}
