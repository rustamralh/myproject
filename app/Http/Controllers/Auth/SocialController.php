<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function getSocialNetwork($socialNetwork)
    {
        if ($socialNetwork == 'amazon' || $socialNetwork == 'google') {
            return Inertia::location(Socialite::driver($socialNetwork)->redirect());
        }
    }


    public function loginWithSocialNetwork($socialNetwork)
    {
        try {
            $user = Socialite::driver($socialNetwork)->user();

            $isUser = User::where('google_id', $user->id)->first();

            if ($isUser) {
                Auth::login($isUser);

                return redirect()->intended('/');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => bcrypt($user->password)// you can change auto generate password here and send it via email but you need to add checking that the user need to change the password for security reasons
                ]);


                Auth::login($newUser);

                return redirect()->intended('/');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
