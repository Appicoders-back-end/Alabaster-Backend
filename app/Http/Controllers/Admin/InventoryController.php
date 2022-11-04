<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Store;
use App\Models\StoreInventory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $baseInventories = Inventory::query();
        $baseInventories->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $inventories = $baseInventories->orderBy('id', 'desc')->paginate(10);

        return view('admin.stores.inventories-listing', ['inventories' => $inventories]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:inventories,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $inventory = new Inventory();
            $inventory->name = $request->name;
            $inventory->save();

            return redirect()->to('admin/inventories')->with('success', __('Inventory has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/inventories')->with('error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|unique:inventories,name,' . $request->id
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $inventory = Inventory::find($request->id);
            $inventory->name = $request->name;
            $inventory->save();

            return redirect()->to('admin/inventories')->with('success', __('Inventory has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/inventories')->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Inventory::find($id)->delete();
            return redirect()->to('admin/inventories')->with('success', __('Inventory has been deleted successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/inventories')->with('error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getStoreInventories(Request $request, $id)
    {
        foreach (Inventory::get() as $row) {
            $storeInventory = StoreInventory::where('inventory_id', $row->id)->where('store_id', $id)->first();
            if ($storeInventory) {
                continue;
            }

            $storeInventory = new StoreInventory();
            $storeInventory->inventory_id = $row->id;
            $storeInventory->store_id = $id;
            $storeInventory->quantity = 0;
            $storeInventory->save();
        }

        $baseStore = Store::with('inventories')->where('id', $id);
        $baseStore->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $store = $baseStore->first();

        return view('admin.stores.store-inventories', ['store' => $store, 'inventories' => $store->inventories]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function editStoreInventories(Request $request, $id)
    {
        $baseStore = Store::with('inventories')->where('id', $id);
        $baseStore->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $store = $baseStore->first();

        return view('admin.stores.edit-store-inventories', ['store' => $store, 'inventories' => $store->inventories]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateStoreInventories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
            'inventories' => 'required|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            foreach ($request->inventories as $row) {
                $inventory = StoreInventory::where('inventory_id', $row['id'])->where('store_id', $request->store_id)->first();

                $storeInventory = $inventory ? $inventory : new StoreInventory();
                $storeInventory->inventory_id = $row['id'];
                $storeInventory->store_id = $request['store_id'];
                $storeInventory->quantity = $row['quantity'];
                $storeInventory->save();
            }
            return redirect()->to('admin/store_inventories/' . $request->store_id)->with('success', __('Inventories has been updated successfully'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/store_inventories/' . $request->store_id)->with('error', $exception->getMessage());
        }
    }
}
