<?php

namespace App\Http\Middleware;

use App\Models\Result;
use App\Models\Test;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsTestCompleted
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
        $id = explode('/', $request->getRequestUri())[3];
        $result = Result::where([
            ['test_id', '=', $id],
            ['user_id', '=', Auth::id()]
        ])->first();

        if ($result) {
            if ($result->status !== null) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard');
    }
}
