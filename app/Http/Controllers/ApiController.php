<?php

namespace App\Http\Controllers;

use App\File;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function auth($token=null, $username, $password, $ipaddress)
    {
        $user = User::where(['email' => $username])->first();
        if ($user && $user->password == $password) {
            return response()->json([
                'token' => str_random(),
                'message' => 'Success'
            ]);
        }
        return response()->json([
            'message' => 'Error'
        ]);
    }
}