<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoriesListResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\StoreResource;
use App\Mail\TestEmail;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Inventory;
use App\Models\Page;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(Request $request)
    {
        $baseCategories = Category::query();
        if (isset($request->name) && $request->name != null) {
            $baseCategories = $baseCategories->where('name', 'like', '%' . $request->name . '%');
        }
        $categories = $baseCategories->get();
        $categories = CategoriesListResource::collection($categories);

        return apiResponse(true, 'Data loaded successfully', $categories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStores(Request $request)
    {
        $baseStores = Store::query();
        if (isset($request->name) && $request->name != null) {
            $baseStores = $baseStores->where('name', 'like', '%' . $request->name . '%');
        }
        $stores = $baseStores->orderBy('id', 'desc')->get();
        $stores = StoreResource::collection($stores);

        return apiResponse(true, 'Data loaded successfully', $stores);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInventories(Request $request)
    {
        $baseInventory = Inventory::query();
        $inventories = $baseInventory->get();
        $inventories = InventoryResource::collection($inventories);

        return apiResponse(true, 'Data loaded successfully', $inventories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersByRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => ['required']
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        $baseUsers = User::with('addresses')->where('role', $request->role);
        $user = auth()->user();
        if ($user->role == User::Contractor) {
            $baseUsers = $baseUsers->where('created_by', $user->id);
        }
        $users = $baseUsers->select('id', 'name', 'role', 'email','working_start_time', 'working_end_time', 'break_time_in', 'break_time_out')->get();

        return apiResponse(true, __('Data loaded successfully'), $users);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function contactQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $data['user_id'] = $request->user()->id;
            $data['title'] = $request->title;
            $data['message'] = $request->message;

            $contact = ContactUs::create($data);
            return apiResponse(true, __('Contact Query has been sent successfully'), $contact);
        } catch (\Exception $e) {
            return apiresponse(false, __('Some error occurred, please try again'), $e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function pages()
    {
        $page = Page::get();
        return apiResponse(true, 'Pages content found', $page);
    }

    /**
     * @return JsonResponse
     */
    public function testNotification()
    {
        try {
            $token = "fkr01h7iTtWY7nPwIx0wHv:APA91bHof4OxfiKIrBm00vK0tlQ7u6fmLmIiuVybJnPaVwRcabXZvjM4ymkG2KeeO2dtRKJhocIMVMo_UZ5wAAMaKYeEd4_TZKTMpIwe3Z6qI7yDHp6PmSAiCWP39xJoMDdhH9dxelgo";
            $title = "Hello from Alabaster";
            $message = sprintf("%s sent you work order request", "Test user");
            SendNotification($token, $title, $message);

            return apiResponse(true, __('Notification has been sent successfully'));
        } catch (\Exception $e) {
            return apiResponse(false, __("Something went wrong"), $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateGetStartedStatus(Request $request)
    {
        $user = $request->user();
        $user->get_started = '1';
        $user->save();

        return apiResponse(true, __('Status has been changed successfully'));
    }

    public function updateUserNames()
    {
        try {
            $users = User::get();
            foreach ($users as $user) {
                $name = explode(' ', $user->name, 2);
                $user->update([
                    'first_name' => $name[0],
                    'last_name' => isset($name[1]) ? $name[1] : null,
                ]);
            }

            return apiResponse(true, "User has been updated successfully");
        } catch (\Exception $exception) {
            return apiResponse(false, $exception->getMessage());
        }
    }

    public function testEmail(Request $request)
    {
        try {
            Mail::to('irfan.haider@appicoders.com')->send(new TestEmail());

            return apiResponse(true, "Email has been sent");
        } catch (Exception $exception) {
            return apiResponse(false, $exception->getMessage());
        }
    }
}
