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
use App\Http\Controllers\Api\CompanyController;

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
Route::get('test-notification', [GeneralController::class, 'testNotification']);
Route::get('test-email', [GeneralController::class, 'testEmail']);
Route::get('pages', [GeneralController::class, 'pages']);
Route::get('updateUserNames', [GeneralController::class, 'updateUserNames']);

Route::group(['middleware' => 'auth:api'], function () {
    /*auth*/
    Route::post('updateProfile', [ContractorCustomerController::class, 'updateProfile']);
    Route::post('updatePassword', [AuthController::class, 'updatePassword']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('getUsers', [MembersController::class, 'getUsers']);
    Route::post('storeMember', [MembersController::class, 'storeMember']);
    Route::get('viewMembersList', [MembersController::class, 'viewMembersList']);
    Route::get('updateGetStartedStatus', [GeneralController::class, 'updateGetStartedStatus']);
    Route::post('updateOnlineStatus', [AuthController::class, 'updateOnlineStatus']);
    Route::post('deleteAccount', [AuthController::class, 'deleteAccount']);
    Route::get('getUserInfo', [AuthController::class, 'getUserInfo']);
    Route::post('deleteAddress', [AuthController::class, 'deleteAddress']);

    /*notifications*/
    Route::get('getUserNotifications', [NotificationsController::class, 'getUserNotifications']);
    Route::get('updateNotificationSetting', [NotificationsController::class, 'updateNotificationSetting']);

    /*payments*/
    Route::post('storeCard', [PaymentMethodController::class, 'storeCard']);
    Route::post('updateDefaultCard/{id}', [PaymentMethodController::class, 'updateDefaultCard']);
    Route::get('showCards', [PaymentMethodController::class, 'showMethod']);
    Route::post('deleteCard', [PaymentMethodController::class, 'deleteCard']);
    Route::post('subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('inAppSubscribe', [SubscriptionController::class, 'inAppSubscribe']);
    Route::get('getSubscriptionPackages', [SubscriptionController::class, 'getSubscriptionPackages']);
    Route::get('getSubscriptionHistory', [SubscriptionController::class, 'getSubscriptionHistory']);

    /*chats*/
    Route::get('chatIndex', [ChatsController::class, 'index']);
    Route::post('chatIndex', [ChatsController::class, 'index']);
    Route::get('viewChatlist/{id}', [ChatsController::class, 'show']);
    Route::post('chatlistCheck', [ChatsController::class, 'checkSessionBeforeMessage']);
    Route::post('sendMessage', [ChatsController::class, 'sendMessage']);

    /*work order Request*/
    Route::post('workRequestsCreate', [WorkRequestController::class, 'store']);
    Route::post('workRequestCustomers', [WorkRequestController::class, 'getWorkRequestCustomers']);
    Route::post('workRequests/{id}', [WorkRequestController::class, 'index']);
    Route::post('declineWorkRequest', [WorkRequestController::class, 'declineWorkRequest']);

    /*dashboard API*/
    Route::get('contractorDashboard', [DashboardController::class, 'getContractorStats']);
    Route::get('cleanerDashboard', [DashboardController::class, 'getCleanerStats']);
    Route::get('customerDashboard', [DashboardController::class, 'getCustomerStats']);

    /*customers*/
    Route::post('customers', [ContractorCustomerController::class, 'index']);
    Route::post('customersCreate', [ContractorCustomerController::class, 'store']);
    Route::post('cleaners', [ContractorCleanerController::class, 'index']);
    Route::post('cleanersCreate', [ContractorCleanerController::class, 'store']);
    Route::post('cleanersUpdate', [ContractorCleanerController::class, 'update']);
    Route::get('activeCleaners', [ContractorCleanerController::class, 'getActiveCleaners']);

    /*companies*/
    Route::post('companies', [CompanyController::class, 'index']);
    Route::post('companies_list', [CompanyController::class, 'getCompaniesList']);
    Route::post('createCompany', [CompanyController::class, 'store']);
    Route::post('updateCompany', [CompanyController::class, 'update']);

    /*members*/
    Route::get('getUsers', [MembersController::class, 'getUsers']);
    Route::post('storeMember', [MembersController::class, 'storeMember']);
    Route::get('viewMembersList', [MembersController::class, 'viewMembersList']);

    /*job listings and reports*/
    Route::post('jobs', [JobController::class, 'index']);
    Route::post('jobsCreate', [JobController::class, 'store']);
    Route::get('jobDetail/{id}', [JobController::class, 'show']);
    Route::post('getUsersByRole', [GeneralController::class, 'getUsersByRole']);
    Route::get('checklist', [JobController::class, 'getAllChecklist']);
    Route::post('checklistCreate', [JobController::class, 'createChecklist']);
    Route::post('contactQuery', [GeneralController::class, 'contactQuery']);
    Route::get('sendChecklist/{id}', [JobController::class, 'sendCheckList']);
    Route::post('getJobsByCleanerId', [JobController::class, 'getJobsByCleanerId']);
    Route::post('assignJobToCleaner', [JobController::class, 'assignJobToCleaner']);
    Route::post('startJob', [JobController::class, 'startJob']);
    Route::post('breakIn', [JobController::class, 'breakIn']);
    Route::post('breakOut', [JobController::class, 'breakOut']);
    Route::post('jobComplete', [JobController::class, 'jobComplete']);
    Route::post('getActiveLocations', [JobController::class, 'getActiveLocations']);
    Route::post('updateInventoryStatus', [JobController::class, 'updateInventoryStatus']);
    Route::post('timeSheet', [JobController::class, 'timeSheet']);
    Route::post('problemReporting', [JobController::class, 'problemReporting']);
    Route::post('weeklyInspections', [JobController::class, 'weeklyInspections']);
    Route::post('completedJobsLocations', [JobController::class, 'getCompletedJobsLocations']);
    Route::post('updateChecklistRemark', [JobController::class, 'updateChecklistRemark']);
    Route::post('checklistReports', [JobController::class, 'checklistReports']);
    Route::post('getCustomerActiveJobs', [JobController::class, 'getCustomerActiveJobs']);
    Route::post('updateChecklist', [JobController::class, 'updateChecklist']);
    Route::post('deleteChecklist', [JobController::class, 'deleteChecklist']);
    Route::post('contractorComment', [JobController::class, 'contractorComment']);
    Route::post('sendJobReportToCustomer', [JobController::class, 'sendReportToCustomer']);
    Route::get('getJobDates', [JobController::class, 'getJobDates']);
});
