<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isSuperUser()) {
            abort(403);
        }

        return $next($request);
    }
}
