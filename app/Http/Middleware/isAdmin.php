<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id === 1) {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Silahkan Login Terlebih Dahulu!');
        // return redirect()->route('dashboard')->with('error', 'Kamu gak punya akses ke halaman ini');
        abort(404);
    }
}
