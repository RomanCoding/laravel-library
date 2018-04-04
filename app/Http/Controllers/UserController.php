<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
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

    /**
     * Return all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Show page to create new user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
