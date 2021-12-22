<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSuperAdmin
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
        if (auth()->user()->is_admin == 1 && auth()->user()->super_admin == 1) {
            return $next($request);
        }
        return redirect()->route('dashboard')->with('error', 'Kamu bukan super admin.');
    }
}
