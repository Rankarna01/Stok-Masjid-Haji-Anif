<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class KoordinatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'koordinator') {
            return $next($request);
        }
        
        return redirect('/')->with('error', 'Akses ditolak. Anda bukan koordinator.');
    }
}