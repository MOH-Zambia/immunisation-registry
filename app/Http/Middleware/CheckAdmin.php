<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Eager load role to prevent N+1 query issue
        $user = Auth::user();

        if (!$user) {
            Flash::error("Unauthorised access");
            return redirect()->route('login');
        }

        // Load role relationship if not already loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        if ($user->role && $user->role->name == "Authenticated User") {
            Flash::error("Unauthorised access");
            return back();
        }

        return $next($request);
    }
}
