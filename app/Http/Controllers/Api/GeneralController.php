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


    public function contactQuery(Request $request){

        $validator = Validator::make($request->all(),[
            'title'         =>      'required',
            'message'       =>      'required',
        ]);
        if($validator->fails())
        return apiResponse(false, implode("\n", $validator->errors()->all()));

        $data['user_id']        =       $request->user()->id;
        $data['title']          =       $request->title;
        $data['message']        =       $request->message;

        $contact = ContactUs::create($data);
        if($contact){
            return apiResponse(true, 'Contact Query has been sent successfully', $contact);
        }
        else{
            return apiresponse(false, 'Some error occurred, please try again');
        }
    }


    public function pages(){
        $page = Page::get();
        return apiResponse(true, 'Pages content found', $page);
    }
}
