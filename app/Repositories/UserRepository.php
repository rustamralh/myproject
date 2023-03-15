<?php

namespace App\Repositories;

use App\Mail\ResetPasswordMessage;
use App\Models\User;
use App\Repositories\Abstraction\ModelRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserRepository extends ModelRepository
{
    protected $model = User::class;

    public function getUserById(int $id, array $relations =[]): User
    {
        return User::with($relations)->findOrFail($id);
    }

    public function create(array $request)
    {
        $user = new User();

        $user->fill($request);

        $user->password    = bcrypt($request['password']);
        $user->status      = 'ActivationPending';
        $user->is_filter   = @$request['is_filter'];

        $user->save();

        if ($user->isUser() && $user->status != 'Active') {
            // Mail::to($user->email)->send(new UserActivation($user->id));
        }

        return $user;
    }

    public function update(array $request, bool $changePassword = false)
    {
        $user       = Auth::user();
        $user->name = $request['name'];

        if ($request['change_password']) {
            $user->password = bcrypt($request['password']);
        }
        $user->update();
        // Mail::to($user->email)->send(new ResetPasswordMessage($user->id));
    }
}
