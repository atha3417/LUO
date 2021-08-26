<?php

namespace App\Http\Middleware;

use App\Models\Test;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsForMe
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
        $test = [];
        $is_for_me = false;

        if (!$request->test) {
            return redirect()->route('dashboard');
        }

        if (in_array(Auth::id(), explode(',', $request->test->for))) {
            $test = $request->test->toArray();
        }

        $for_me = in_array($request->test->id, $test);
        $key = array_keys($test, $request->test->id);


        if ($for_me == true && $key) {
            if ($key[0] == 'id') {
                $is_for_me = true;
            }
        }

        if ($is_for_me) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}
