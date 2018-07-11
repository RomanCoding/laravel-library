<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['network']);
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

        $user = User::create(array_merge($validatedUser, [
            'access_level' => 1,
        ]));
        if ($request->expectsJson()) {
            return response()->json($user, 201);
        }
        return back()->with('success_message', 'User added');
    }

    public function update(User $user, Request $request)
    {
        $validatedUser = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'access_level' => 'required|integer|min:1|max:3',
            'business_name' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'network_visible' => 'nullable|boolean',
            'suburb' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
        ]);

        return $user->update($validatedUser) ? response('', 204) : response('', 400);
    }

    public function network()
    {
        return User::visible()->orderBy('business_name', 'asc')->get();
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

    public function usersComponent()
    {
        return view('admin.users', [
            'users' => User::all()
        ]);
    }
}
