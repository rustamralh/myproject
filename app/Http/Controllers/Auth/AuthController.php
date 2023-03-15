<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\AuthService;

class AuthController extends Controller
{
    public AuthService $authService;

    public UserRepository $userRepository;

    public function __construct()
    {
        $this->authService    = new AuthService();
        $this->userRepository = new UserRepository();
    }
}
