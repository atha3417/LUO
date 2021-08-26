<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


Route::get('/cbt/admin', [AdminController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'active', 'admin', 'no_test_in_progress']);

Route::prefix('/cbt/admin/manage')->middleware(['auth', 'active', 'admin', 'no_test_in_progress'])->group(function () {
    Route::prefix('/users')->group(function () {
        Route::get('/', [AdminController::class, 'manage_users'])->name('admin.manage.users');
        Route::get('/create', [AdminController::class, 'manage_users_create'])->name('admin.manage.users.create');
        Route::post('/', [AdminController::class, 'manage_users_store'])->name('admin.manage.users.store');
        Route::post('/{user}', [AdminController::class, 'manage_users_toggle_activate'])->name('admin.manage.users.toggle-active');
        Route::get('/{user}/edit', [AdminController::class, 'manage_users_edit'])->name('admin.manage.users.edit');
        Route::put('/', [AdminController::class, 'manage_users_update'])->name('admin.manage.users.update');
        Route::delete('/{user}', [AdminController::class, 'manage_users_destroy'])->name('admin.manage.users.delete');
    });

    Route::prefix('/tests')->group(function () {
        Route::get('/', [AdminController::class, 'manage_tests'])->name('admin.manage.tests');
        Route::get('/create', [AdminController::class, 'manage_tests_create'])->name('admin.manage.tests.create');
        Route::get('/{test}', [AdminController::class, 'manage_tests_show'])->name('admin.manage.tests.show');
        Route::post('/{test}/get-participants', [AdminController::class, 'manage_tests_participants'])->name('admin.manage.tests.get_participants');
        Route::post('/', [AdminController::class, 'manage_tests_store'])->name('admin.manage.tests.store');
        Route::post('{test}/users/save', [AdminController::class, 'manage_tests_user_save'])->name('admin.manage.tests.users.save');
        Route::get('/{test}/edit', [AdminController::class, 'manage_tests_edit'])->name('admin.manage.tests.edit');
        Route::get('/{test}/users', [AdminController::class, 'manage_tests_user'])->name('admin.manage.tests.users');
        Route::put('/', [AdminController::class, 'manage_tests_update'])->name('admin.manage.tests.update');
        Route::delete('/{test}', [AdminController::class, 'manage_tests_destroy'])->name('admin.manage.tests.delete');
    });
});
