<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // @todo here will be admin middleware
    }

    /**
     * Register new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedUser = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'business_name' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
        ]);

        User::create(array_merge($validatedUser, [
            'password' => Hash::make($validatedUser['password'])
        ]));

        return back()->with('success_message', 'User added');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
