<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->access_level == User::MAX_LEVEL) {
            return $next($request);
        }
        if ($request->expectsJson()) {
            return response()->json('Permission denied', 401);
        }
        return redirect('/library');
    }
}
