<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user || !$user->isAdmin()) {
                throw new \Exception('Unauthorized');
            }

            return $next($request);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }


}
