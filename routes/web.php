<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

// ----------------------------------------------------------------------------------------------
# common setting
Route::get('/ifs_mainhall.html', function () {
    return redirect('/');
});

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// 談話室
Route::get('danwa', [\App\Http\Controllers\ChatDanwaController::class, 'index']);
Route::get('danwa/store', [\App\Http\Controllers\ChatDanwaController::class, 'store']);
Route::post('danwa', [\App\Http\Controllers\ChatDanwaController::class, 'store']);
Route::get('danwa/log', [\App\Http\Controllers\ChatDanwaController::class, 'get_log']);

// ----------------------------------------------------------------------------------------------
require __DIR__.'/auth.php';

// 管理者用ルート
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        Route::resource('admin_infos', \App\Http\Controllers\Admin\AdminInfoController::class)
            ->only(['index','create','store','edit','update','destroy']);
    });

// ユーザー用ルート
Route::middleware(['auth', AdminMiddleware::class . ':user'])
    ->group(function () {
        Route::get('dashboard', fn() => view('users.dashboard'))->name('user.dashboard');
    });
