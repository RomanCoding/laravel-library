<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
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
     * @param StoreUser $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUser $request)
    {
        $user = User::create(array_merge($request->validated(), [
            'access_level' => 1,
        ]));
        if ($request->expectsJson()) {
            return response()->json($user, 201);
        }
        return back()->with('success_message', 'User added');
    }

    /**
     * @param User $user
     * @param UpdateUser $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(User $user, UpdateUser $request)
    {
        return $user->update($request->validated()) ? response('', 204) : response('', 400);
    }

    /**
     * Return collection of users who should be visible in network page.
     *
     * @return mixed
     */
    public function network()
    {
        return User::visible()->orderBy('business_name')->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return array
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        return [
            'success' => $user->delete()
        ];
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
