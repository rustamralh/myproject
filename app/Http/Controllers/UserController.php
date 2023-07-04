<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    public function index()
    {
        return  User::query()->get();
        // return  User::search('user123')->get();
    }
    public function download(Request $request)
    {
        return (new UserExport(['id','name'], ['id','name']))->download('users.xlsx');
        // return (new UserExport())>download('users.xlsx')->deleteFileAfterSend(true);
    }

    public function show(int $id)
    {
        return $this->userRepository->getUserById($id);
    }
    public function store(StoreUserRequest $request)
    {
        return $this->userRepository->createUser($request->validated());
    }
}
