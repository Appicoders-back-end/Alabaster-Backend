<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\Contractor\CustomerController as ContractorCustomerController;
use App\Http\Controllers\Api\Contractor\CleanerController as ContractorCleanerController;
use App\Http\Controllers\Api\WorkRequestController;
use App\Http\Controllers\Api\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('login', [AuthController::class, 'login']);
Route::post('signup', [AuthController::class, 'signUp']); //only contractor can signup using mobile app
Route::post('forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('verify_forgot_code', [AuthController::class, 'verifyForgotCode']);
Route::post('change_password', [AuthController::class, 'changePassword']);
Route::get('categories', [GeneralController::class, 'getCategories']);
Route::get('stores', [GeneralController::class, 'getStores']);
Route::get('inventories', [GeneralController::class, 'getInventories']);
Route::get('urgencies', [GeneralController::class, 'getUrgencies']);
Route::get('customerLocations/{id}', [ContractorCustomerController::class, 'getLocations']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('customers', [ContractorCustomerController::class, 'index']);
    Route::post('customersCreate', [ContractorCustomerController::class, 'store']);
    Route::get('customers/{id}', [ContractorCustomerController::class, 'show']);
    Route::post('cleaners', [ContractorCleanerController::class, 'index']);
    Route::post('cleanersCreate', [ContractorCleanerController::class, 'store']);
    Route::get('cleanerDetail/{id}', [ContractorCleanerController::class, 'show']);
    Route::post('workRequestsCreate', [WorkRequestController::class, 'store']);
    Route::get('workRequests', [WorkRequestController::class, 'index']);
    Route::get('workRequests/{task}', [WorkRequestController::class, 'show']);
    Route::get('jobs', [TaskController::class, 'index']);
    Route::post('jobsCreate', [TaskController::class, 'store']);
});
