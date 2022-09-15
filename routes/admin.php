<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubscriptionController;

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
        /*users*/
        Route::get('customers', [UserController::class, 'customers'])->name('admin.customers');
        Route::get('cleaners', [UserController::class, 'cleaners'])->name('admin.cleaners');
        Route::get('contractors', [UserController::class, 'contractors'])->name('admin.contractors');
        Route::post('updateUserStatus/{id}', [UserController::class, 'updateStatus'])->name('admin.updateUserStatus');
        /*contact queries*/
        Route::get('contact-queries', [AdminController::class, 'contactQueries'])->name('admin.contact-queries');
        /*subscriptions*/
        Route::resource('subscriptions', SubscriptionController::class);
        Route::post('update_subscription', [SubscriptionController::class, 'update'])->name('admin.update_subscription');
        Route::get('delete_subscription/{id}', [SubscriptionController::class, 'delete'])->name('admin.delete_subscription');
        /*categories*/
        Route::get('categories', [AdminController::class, 'categories'])->name('admin.categories');
        /*pages*/
        Route::get('terms', [AdminController::class, 'terms'])->name('admin.terms');
        Route::get('privacy', [AdminController::class, 'privacy'])->name('admin.privacy');
        Route::post('update-page', [AdminController::class, 'updatePage'])->name('admin.update-page');
        /*payments*/
        Route::get('payments', [AdminController::class, 'payments'])->name('admin.payments');
        Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
});
