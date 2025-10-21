<?php

namespace App\Traits;

use App\Exceptions\AuthException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Enum\UserRoles;

use App\Models\Auth\Role;
use Exception;

trait AuthTraits
{
    /**
     * @param LoginRequest $validated
     */
    public function authenticate($validated)
    {
        // checks if the user exists on db
        $user = $this->model->where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw new Exception('Invalid credentials provided');
        }

        Auth::login($user);

        session()->regenerate();
        session()->save();

        return $user;
    }

    /**
     * @param RegisterRequest $validated
     * @return array
     */
    public function register($validated)
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
            throw new Exception('User registration failed');
        }

        return $user;
    }

    public function logout($user)
    {
        // Checks if user is authenticated
        if (!$user) {
            throw new Exception('User is not authenticated');
        }
        // logs out user
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();
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
        // Check if user exists and has a role
        if (!$user || !$user->role) {
            return route('login');
        }

        // Return matched route based on user role
        return match ($user->role->role_name) {
            'admin',      'manager'    => route('dashboard.home'),
            'accountant', 'guest'      => route('cashier.home'),
            default                    => route('cashier.home')
        };
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
            return [
                'error' => true,
                'message' => 'User already exists',
                'next' => null,
                'data' => []
            ];
        }
    }
}
