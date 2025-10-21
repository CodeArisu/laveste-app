<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UserRequest\StoreRequest;
use App\Http\Requests\UserRequest\UpdateRequest;
use App\Services\UserService;

class UserController extends BaseController
{
    public function __construct(
        // user service injection
        protected UserService $userService
    ) {}

    // users data page
    public function index()
    {
        $user = $this->userService->getUser();
        return view('src.admin.users', ['users' => $user]);
    }

    public function create()
    {
        // return view('src.admin.users.adddetails');
    }

    public function store(StoreRequest $request, $user)
    {
        $response = $this->userService->storeRequest($request, $user);
        return $this->getResponse($response);
    }

    // users edit form page
    public function edit()
    {
        return view('src.admin.users.edituser');
    }

    /**
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $response = $this->userService->updateRequest($request);
        return $this->getResponse($response);
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

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable($id)
    {
        $response = $this->userService->disableRequest($id);
        return $this->getResponse($response);
    }
}
