<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoriesListResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\StoreResource;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Store;
use App\Models\Urgency;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getCategories(Request $request)
    {
        $baseCategories = Category::query();
        $categories = $baseCategories->get();
        $categories = CategoriesListResource::collection($categories);

        return apiResponse(true, 'Data loaded succesfully', $categories);
    }

    public function getStores(Request $request)
    {
        $stores = Store::orderBy('id', 'desc')->get();
        $stores = StoreResource::collection($stores);

        return apiResponse(true, 'Data loaded succesfully', $stores);
    }

    public function getInventories(Request $request)
    {
        $baseInventory = Inventory::query();
        $inventories = $baseInventory->get();
        $inventories = InventoryResource::collection($inventories);

        return apiResponse(true, 'Data loaded succesfully', $inventories);
    }

    public function getUrgencies(Request $request)
    {
        $urgencies = Urgency::select('id', 'name')->get();

        return apiResponse(true, 'Data loaded succesfully', $urgencies);
    }
}
