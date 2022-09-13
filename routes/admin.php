<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;

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
        Route::get('/', [UserController::class, 'customers']);
        Route::get('customers', [UserController::class, 'customers'])->name('admin.customers');
        Route::get('cleaners', [UserController::class, 'cleaners'])->name('admin.cleaners');
        Route::get('contractors', [UserController::class, 'contractors'])->name('admin.contractors');
        Route::get('subscriptions', [AdminController::class, 'subscriptions'])->name('admin.subscriptions');
        Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
});
