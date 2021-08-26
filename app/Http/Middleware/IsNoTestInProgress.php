<?php

namespace App\Http\Middleware;

use App\Models\Result;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsNoTestInProgress
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
            ['user_ended', '=', null],
            ['user_id', '=', Auth::id()]
        ])->first();

        if ($result) {
            return redirect()->route('cbt.test.do', $result->test_id);
        }

        return $next($request);
    }
}
