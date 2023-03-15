<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository   = new UserRepository();
    }

    public function getLoggedInUser()
    {
        return auth()->user();
    }

    public function loginUser(User|int $user)
    {
        if (is_int($user)) {
            $user = $this->userRepository->getUserById($user);
        }

        Auth::login($user);
    }

    public function logout()
    {
        $adminUserLoggedIn = null;
        if (request()->session()->get('login_as_user')) {
            $adminUserLoggedIn = request()->session()->get('admin_user_logged_in');
        }

        //Logout
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        if ($adminUserLoggedIn) {
            $this->loginUser($adminUserLoggedIn);

            return to_route('admin.users.index');
        }

        return redirect('/');
    }

    public function fakeLogin(User $user)
    {
        //Get logged in admin user
        $loggedInAdminUser = $this->getLoggedInUser();

        //Logout existing user
        $this->logout();

        //Login new user
        $this->loginUser($user);

        //Set login as in session
        request()->session()->put('login_as_user', true);
        request()->session()->put('admin_user_logged_in', $loggedInAdminUser->id);
    }
}
