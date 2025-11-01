<?php

namespace App\Http\Controllers;

use App\Events\SlackNotificationEvent;
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
        // Send Slack notification about user export completion
        event(new SlackNotificationEvent(
            '#general',
            'User export completed successfully',
            [],
            [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => '*User Export Completed*\nA user export has been generated and downloaded.',
                    ],
                ],
            ]
        ));
        
        return (new UserExport(['id','name'], ['id','name']))->download('users.xlsx');
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
