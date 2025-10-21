<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest\UpdateRequest;
use App\Services\UserService;

class UserController
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        $user = $this->userService->getUser();
        return view('src.admin.users', ['users' => $user]);
    }

    public function edit()
    {
        return view('src.admin.users.edituser');
    }

    public function update(UpdateRequest $request)
    {
        $response = $this->userService->updateRequest($request);
        return redirect()->route($response)
            ->with('success', 'User successfully updated');
    }

    // public function update(User $user, Request $request)
    // {
    //     $validated = Validator::make($request->all(), [
    //         'name' => 'sometimes|string|max:25',
    //         'email' => 'sometimes|email|max:25',
    //         'first_name' => 'sometimes|string|max:25',
    //         'last_name' => 'sometimes|string|max:25',
    //         'address' => 'sometimes|string',
    //         'contact' => 'sometimes|string|max:11',
    //     ])->validate();

    //     try {
    //         // Update user
    //         $user->update([
    //             'name' => $validated['name'],
    //             'email' => $validated['email'],
    //         ]);

    //         // Update or create user details
    //         $user->userDetail()->updateOrCreate(
    //             ['user_id' => $user->id],
    //             [
    //                 'first_name' => $validated['first_name'],
    //                 'last_name' => $validated['last_name'],
    //                 'contact' => $validated['contact'],
    //                 'address' => $validated['address'],
    //             ]
    //         );

    //         return redirect()->back()->with('success', 'User updated');
    //     } catch (\Exception $e) {
    //         throw new \Exception($e->getMessage());
    //     }
    // }

    // public function disable(User $user)
    // {
    //     $user->update(['disabled_at' => now()]);
    //     return redirect()->back()->with('success', 'User disabled');
    // }
}
