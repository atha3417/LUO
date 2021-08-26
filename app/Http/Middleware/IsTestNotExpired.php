<?php

namespace App\Http\Middleware;

use App\Models\Test;
use Closure;
use Illuminate\Http\Request;

class IsTestNotExpired
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
        $test = Test::find($id);

        $start_test_date = explode('-', explode(' ', $test->start_test)[0]);
        $start_test_time = explode(':', explode(' ', $test->start_test)[1]);
        $start_test = mktime($start_test_time[0], $start_test_time[1], $start_test_time[2], $start_test_date[1], $start_test_date[2], $start_test_date[0]);

        $end_test_date = explode('-', explode(' ', $test->end_test)[0]);
        $end_test_time = explode(':', explode(' ', $test->end_test)[1]);
        $end_test = mktime($end_test_time[0], $end_test_time[1], $end_test_time[2], $end_test_date[1], $end_test_date[2], $end_test_date[0]);

        $now = now();
        $current_date = explode('-', explode(' ', $now)[0]);
        $current_time = explode(':', explode(' ', $now)[1]);
        $current = mktime($current_time[0], $current_time[1], $current_time[2], $current_date[1], $current_date[2], $current_date[0]);

        if ($start_test <= $current && $current <= $end_test) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}
