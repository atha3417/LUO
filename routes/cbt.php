<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::prefix('cbt')->middleware(['auth', 'active'])->group(function () {
    Route::middleware(['no_test_in_progress'])->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('dashboard');
        Route::get('confirm-test/{test}', [TestController::class, 'show'])
            ->middleware(['for_me', 'test_not_started', 'not_expired'])->name('cbt.test.confirm');
        Route::get('detail-test/{test}', [TestController::class, 'detail_test'])
            ->middleware(['completed', 'for_me']);
        Route::post('start-test/{test}', [TestController::class, 'start_test'])
            ->middleware(['for_me'])->name("cbt.test.start");
    });

    Route::get('/save-time-left/{test}', [TestController::class, 'save_unix_time_left']);
    Route::post('/get-time-left/{test}', [TestController::class, 'get_unix_time_left']);
    Route::post('/get-all-question/{test}', [TestController::class, 'all_questions']);
    Route::post('save-answer/{quiz}', [TestController::class, 'save_answer'])
        ->name("cbt.test.save");
    Route::post('save-doubt/{quiz}', [TestController::class, 'save_doubt'])
        ->name("cbt.test.save_as_doubt");
    Route::post('get-question/{quiz}', [TestController::class, 'get_question'])
        ->name("cbt.test.get_question");
    Route::post('finish-test/{test}', [TestController::class, 'finish_test'])
        ->middleware(['for_me'])->name('cbt.test.finish');
    Route::post('validate-test/{test}', [TestController::class, 'validate_before_finish_test'])
        ->middleware(['for_me'])->name('cbt.test.validate');
    Route::get('get-question/{quiz}', [TestController::class, 'get_question']);
    Route::get('do-test/{test}', [TestController::class, 'do_test'])
        ->middleware(['not_completed', 'for_me', 'test_started', 'test_not_ended'])->name('cbt.test.do');
});
