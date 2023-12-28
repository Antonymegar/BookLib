<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;


class Authenticate
{
    /**
     * checking whether the user is authenticated.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('api')->guest()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
