<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/cbt')->middleware(['auth']);
Route::get('/live-clock', [TestController::class, 'live_clock']);
Route::get('/block-mobile', function () {
    abort(403);
});

require __DIR__ . '/cbt.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
