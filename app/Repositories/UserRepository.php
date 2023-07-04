<?php

namespace App\Repositories;

use App\Mail\ResetPasswordMessage;
use App\Models\Post;
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

    public function createUser(array $request): User
    {
        $user = User::create($request);
        $this->saveChildRecords($user, $request);
        return $user->load('posts');
    }
    public function saveChildRecords($user, array $request)
    {
        $this->savePosts($user, $request['posts']);
    }
    public function savePosts($user, array $request)
    {
        $posts = collect();
        if (! is_null($request)) {
            foreach ($request as $postRequest) {
                $post = new Post();
                if (!is_null(@$postRequest['id'])) {
                    $post = Post::findOrFail($postRequest['id']);
                }
                $post->fill($postRequest);
                $post->user()->associate($user);
                $post->save();
                $posts->push($post);
            }
        }
    }
}
