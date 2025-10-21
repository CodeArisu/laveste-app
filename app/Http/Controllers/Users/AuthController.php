<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\BaseController;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest\LoginRequest;
use App\Http\Requests\UserRequest\RegisterRequest;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function __construct(
        // auth service injection
        protected AuthService $authService
    ) {}

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerUser(RegisterRequest $request)
    {
        $response = $this->authService->registerRequest($request);
        return $this->getResponse($response);
    }

    // Registration form page
    public function registerIndex()
    {
        return view('src.dashboard.users.register');
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginUser(LoginRequest $request)
    {
        $response = $this->authService->loginRequest($request);
        return $this->getResponse($response);
    }

    // Login form page
    public function loginIndex()
    {
        return view('src.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutUser(Request $request)
    {
        $response = $this->authService->logoutRequest($request);
        return $this->getResponse($response);
    }
}
