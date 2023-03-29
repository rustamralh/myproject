<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RegisterController extends AuthController
{
    /**
     * Display register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle account registration request
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user                    = $this->userRepository->create($request->validated());

        //Login user
        $this->authService->loginUser($user);

        //Registered event
        event(new Registered($user));

        //Redirect to login
        return Redirect::route('dashboard.index')->with('success', 'Account successfully registered.');
    }
}
