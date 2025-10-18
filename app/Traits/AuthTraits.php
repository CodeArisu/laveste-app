<?php

namespace App\Traits;

use App\Exceptions\AuthException;
use Illuminate\Support\Facades\Hash;

use App\Enum\UserRoles;

use App\Models\Auth\Role;

trait AuthTraits
{
    /**
     * @param LoginRequest $validated
     */
    public function loginUser($validated)
    {
        // checks if the user exists on db
        $user = $this->model->where('email', $validated['email'])->first();

        // verifies the password
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw AuthException::invalidUserCredentials();
        }

        return $user;
    }

    /**
     * @param RegisterRequest $validated
     * @return array
     */
    public function registerUser($validated)
    {
        // checks if roles exist, if not creates them
        if (!Role::exists()) {
            $this->registerRoles();
        }

        // checks if user already exists
        $this->checkIfExists($validated);

        // creates new user
        $user = $this->handleRegister($validated);

        if (!$user) {
            throw AuthException::userRegistrationFailed();
        }

        return $user;
    }

    public function logoutUser($user)
    {
        // Checks if user is authenticated
        if (!$user) {
            throw AuthException::unauthenticated('No authenticated user found');
        }
        // logs out user
        $user->logout();
    }

    /**
     * @param AuthRequest $request
     * @return array
     */
    public function handleRegister($validated)
    {
        // filters user data
        $data = $this->filterUser($validated);

        // creates user
        return $this->model->create($data);
    }

    /**
     * @param UpdateRequest $validated
     * @return void
     */
    public function updateUser($id, $validated)
    {
        // update user logic to be implemented
        $user = $this->model->update($id, $validated);

        if (!$user) {
            throw AuthException::userNotFound();
        }
    }

    /**
     * @param array $validated
     * @return array
     */
    public function filterUser($validated)
    {
        return [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => UserRoles::GUEST->value,
        ];
    }

    /**
     * @param User $user
     */
    public function userRedirect($user)
    {
        // returns matched route and message based on user role
        return !$user ? match ($user->role->role_name) {
            'admin'        => redirect()->route('dashboard.home'),
            'manager'      => redirect()->route('dashboard.home'),
            'accountant'   => redirect()->route('cashier.home'),
            'guest'        => redirect()->route('cashier.home'),
            default        => redirect()->back(),
        } : [redirect()->route('login')];
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
     * @param Request $request
     */
    private function checkIfExists($request)
    {
        $exist = $this->model->where('email', $request['email'])
            ->orWhere('name', $request['name'])
            ->exists();

        if ($exist) {
            throw AuthException::userAlreadyRegistered();
        }
    }
}
