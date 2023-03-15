<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class LogoutController extends AuthController
{
    public function index(Request $request)
    {
        return $this->authService->logout();
    }
}
