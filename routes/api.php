<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\Contractor\CustomerController as ContractorCustomerController;
use App\Http\Controllers\Api\Contractor\CleanerController as ContractorCleanerController;
use App\Http\Controllers\Api\WorkRequestController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\MembersController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\DashboardController;

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
Route::post('stores', [GeneralController::class, 'getStores']);
Route::get('inventories', [GeneralController::class, 'getInventories']);
Route::get('customerLocations/{id}', [ContractorCustomerController::class, 'getLocations']);

Route::group(['middleware' => 'auth:api'], function () {
    /*auth*/
    Route::post('updateProfile',[ContractorCustomerController::class, 'updateProfile']);
    Route::post('updatePassword', [AuthController::class, 'updatePassword']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('getUsers', [MembersController::class, 'getUsers']);
    Route::post('storeMember', [MembersController::class, 'storeMember']);
    Route::get('viewMembersList', [MembersController::class, 'viewMembersList']);

    /*notifications*/
    Route::get('getUserNotificaions', [NotificationsController::class, 'getUserNotificaions']);

    /*payments*/
    Route::post('storeCard', [PaymentMethodController::class, 'storeCard']);
    Route::post('updateDefaultCard/{id}', [PaymentMethodController::class, 'updateDefaultCard']);
    Route::get('showCards', [PaymentMethodController::class, 'showMethod']);
    Route::post('deleteCard', [PaymentMethodController::class, 'deleteCard']);
    Route::post('subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('getSubscriptionPackages', [SubscriptionController::class, 'getSubscriptionPackages']);
    Route::get('getSubscriptionHistory', [SubscriptionController::class, 'getSubscriptionHistory']);

    /*chats*/
    Route::get('chatIndex', [ChatsController::class, 'index']);
    Route::get('viewChatlist/{id}', [ChatsController::class, 'show'] );
    //    Route::post('chatlistCheck', 'ChatsController@chatlistCheck');
    Route::post('sendMessage', [ChatsController::class, 'sendMessage']);
    Route::get('chatIndex', [ChatsController::class, 'index']);
    Route::get('viewChatlist/{id}', [ChatsController::class, 'show'] );
    Route::post('chatlistCheck', [ChatsController::class, 'checkSessionBeforeMessage']);
    Route::post('sendMessage', [ChatsController::class, 'sendMessage']);

    Route::post('customers', [ContractorCustomerController::class, 'index']);
    Route::post('customersCreate', [ContractorCustomerController::class, 'store']);
    Route::post('cleaners', [ContractorCleanerController::class, 'index']);
    Route::post('cleanersCreate', [ContractorCleanerController::class, 'store']);
    Route::get('activeCleaners', [ContractorCleanerController::class, 'getActiveCleaners']);
    Route::post('workRequestsCreate', [WorkRequestController::class, 'store']);
    Route::post('workRequestCustomers', [WorkRequestController::class, 'getWorkRequestCustomers']);
    Route::get('workRequests/{id}', [WorkRequestController::class, 'index']);
    Route::post('jobs', [JobController::class, 'index']);
    Route::post('jobsCreate', [JobController::class, 'store']);
    Route::get('jobDetail/{id}', [JobController::class, 'show']);
    Route::post('getUsersByRole', [GeneralController::class, 'getUsersByRole']);
    Route::get('checklist', [JobController::class, 'getAllChecklist']);
    Route::post('checklistCreate', [JobController::class, 'createChecklist']);
    Route::post('contactQuery', [GeneralController::class, 'contactQuery']);
    Route::get('pages', [GeneralController::class, 'pages']);
    Route::get('getUsers', [MembersController::class, 'getUsers']);
    Route::post('storeMember', [MembersController::class, 'storeMember']);
    Route::get('viewMembersList', [MembersController::class, 'viewMembersList']);
    Route::get('sendChecklist/{id}', [JobController::class, 'sendCheckList']);
    Route::post('getJobsByCleanerId', [JobController::class, 'getJobsByCleanerId']);
    Route::post('assignJobToCleaner', [JobController::class, 'assignJobToCleaner']);
    Route::get('pages', [GeneralController::class, 'pages']);
    Route::post('startJob', [JobController::class, 'startJob']);
    Route::post('breakIn', [JobController::class, 'breakIn']);
    Route::post('breakOut', [JobController::class, 'breakOut']);
    Route::post('jobComplete', [JobController::class, 'jobComplete']);
    Route::post('getActiveLocations', [JobController::class, 'getActiveLocations']);
    Route::get('contractorDashboard', [DashboardController::class, 'getContractorStats']);
    Route::get('cleanerDashboard', [DashboardController::class, 'getCleanerStats']);
    Route::get('customerDashboard', [DashboardController::class, 'getCustomerStats']);
    Route::post('updateInventoryStatus', [JobController::class, 'updateInventoryStatus']);
});
