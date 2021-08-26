<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function time_larger_than($first, $second)
    {
        $first_date = explode('-', explode(' ', $first)[0]);
        $first_time = explode(':', explode(' ', $first)[1]);
        $first = mktime($first_time[0], $first_time[1], $first_time[2], $first_date[1], $first_date[2], $first_date[0]);

        $second_date = explode('-', explode(' ', $second)[0]);
        $second_time = explode(':', explode(' ', $second)[1]);
        $second = mktime($second_time[0], $second_time[1], $second_time[2], $second_date[1], $second_date[2], $second_date[0]);

        $now = now();
        $current_date = explode('-', explode(' ', $now)[0]);
        $current_time = explode(':', explode(' ', $now)[1]);
        $current = mktime($current_time[0], $current_time[1], $current_time[2], $current_date[1], $current_date[2], $current_date[0]);

        if ($first <= $current && $current <= $second) {
            return true;
        }
        return false;
    }
}
