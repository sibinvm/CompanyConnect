<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->is_admin) {
            return $next($request);
        } else {
            if (!$request->expectsJson()) {
                Auth::logout();
                
                return redirect()->route('login')->with('unauthorized', 'You are not authorized to access this page.');
            } else {
                $request->user()->currentAccessToken()->delete();

                return response()->json(['message' => 'You are not authorized to access this Api.']);
            }
        }
    }
}
