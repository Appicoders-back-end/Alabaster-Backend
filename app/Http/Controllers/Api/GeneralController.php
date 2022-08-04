<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoriesListResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\StoreResource;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Store;
use Illuminate\Http\Request;

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

        return apiResponse(true, 'Data loaded succesfully', $inventories);
    }
}
