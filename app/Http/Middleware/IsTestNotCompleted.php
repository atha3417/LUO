<?php

namespace App\Http\Middleware;

use App\Models\Result;
use App\Models\Test;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsTestNotCompleted
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
        $result = Result::where([
            ['test_id', '=', $request->test->id],
            ['user_id', '=', Auth::id()]
        ])->first();

        if ($result) {
            if ($result->status === null || $result->status === 0 || $result->status === false) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard');
    }
}
