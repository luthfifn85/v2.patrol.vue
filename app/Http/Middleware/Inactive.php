<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Inactive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->is_active ==  2)
        {
            Auth::user()->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'status: inactive'
            ], 401);
        }

        return $next($request);
    }
}
