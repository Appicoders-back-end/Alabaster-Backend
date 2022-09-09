<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {

    Route::get('login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('do_login', [AuthController::class, 'doLogin'])->name('admin.do_login');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('logout', [AuthController::class, 'logout']);
    });
});
