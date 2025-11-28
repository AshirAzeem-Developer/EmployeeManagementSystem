<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check karein agar user login hai aur uska role 'admin' hai
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Agar admin nahi hai, toh wapis home page ya 403 error de dein
        return redirect('/dashboard')->with('error', 'Aap ke paas is page tak rasai ka ikhtiyar nahin hai.');
        // Ya
        // abort(403, 'Unauthorized action.');
    }
}
