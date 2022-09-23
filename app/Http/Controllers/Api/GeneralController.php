<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoriesListResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\StoreResource;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Inventory;
use App\Models\Page;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        return apiResponse(true, 'Data loaded succesfully', $categories);
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
        $users = $baseUsers->select('id', 'name', 'role', 'email')->get();

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
            $token = "cQjgKud0Rj6XQ74hQYVD9y:APA91bHPeKr5F4AZpywYc226DVn6FVq7oaKzvqq4AaW4T4HFXQGfvVaK18l0AXI0BqOpnFBH5gtayuYovI1slN84k8K85V07oKEFtmOI9yygBpOYxVdQI0pM-lFPyr_ATVuxi9AYQp1y";
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
}
