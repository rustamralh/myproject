<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        return  User::search('user123')->get();
    }
    public function download(Request $request)
    {
        return (new UserExport(['id','name'], ['id','name']))->download('users.xlsx');
        // return (new UserExport())>download('users.xlsx')->deleteFileAfterSend(true);
    }
}
