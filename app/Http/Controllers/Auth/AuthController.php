<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Requests\AuthRequest;
use App\Models\Auth\User;
use App\Models\Auth\UserDetail;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class AuthController extends ApiBaseController
{
    public function __construct(protected AuthService $authService) {}

    public function registerUser(AuthRequest $request)
    {
        $user = $this->authService->registerRequest($request);
        return redirect()->route($user['route'])->with('success', $user['message']);
    }

    public function registerIndex()
    {
        return view('src.admin.users.register');
    }

    public function loginUser(AuthRequest $request)
    {
        $user = $this->authService->loginRequest($request);
        return redirect()->route($user['route'])->with(['success' => $user['message']]);
    }

    public function loginIndex()
    {
        return view('src.login');
    }

    public function logoutUser(Request $request)
    {
        $user = $this->authService->logoutRequest($request);

        return redirect()->route($user['url'])->with(['success' => $user['message']]);
    }

    public function displayUsers()
    {
        $user = User::all();
        return view('src.admin.users', ['users' => $user]);
    }

    public function edit(User $user)
    {
        $userData = User::with('userDetail')->findOrFail($user->id);

        return view('src.admin.users.edituser', ['user' => $userData]);
    }

    public function update(User $user, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:25',
            'email' => 'sometimes|email|max:25',
            'first_name' => 'sometimes|string|max:25',
            'last_name' => 'sometimes|string|max:25',
            'address' => 'sometimes|string',
            'contact' => 'sometimes|string|max:11',
        ])->validate();

        try {
            // Update user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update or create user details
            $user->userDetail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'contact' => $validated['contact'],
                    'address' => $validated['address'],
                ]
            );

            return redirect()->back()->with('success', 'User updated');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function disable(User $user)
    {
        $user->update(['disabled_at' => now()]);
        return redirect()->back()->with('success', 'User disabled');
    }
}
