<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Store;
use App\Models\StoreAddress;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $baseStores = Store::query();
        $baseStores->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $stores = $baseStores->orderBy('id', 'desc')->paginate(10);

        return view('admin.stores.stores-listing', ['stores' => $stores]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        
        return view('admin.stores.create-store');
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:stores,name',
            'addresses' => 'required|array',
            'addresses.*.address' => 'required',
            // 'addresses.*.latitude' => 'required',
            // 'addresses.*.longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $store = new Store();
            $store->name = $request->name;
            if ($request->file('image')) {
                $store->image = saveFile($request->file('image'));
            }
            $store->save();

            foreach ($request->addresses as $row) {
                $address = new StoreAddress();
                $address->store_id = $store->id;
                $address->address = $row['address'];
                $address->lat = $row['latitude'];
                $address->lng = $row['longitude'];
                $address->save();
            }

            return redirect()->to('admin/stores')->with('success', __('Store has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/stores')->with('error', $exception->getMessage());
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
            'name' => 'required|unique:inventories,name,', $request->id,
            'addresses' => 'required|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $store = Store::find($request->id);
            $store->name = $request->name;
            if ($request->file('image')) {
                $store->image = saveFile($request->file('image'));
            }
            $store->save();

            foreach ($request->addresses as $row) {
                if (isset($row['address_id'])) {
                    $storeAddress = StoreAddress::find($row['address_id']);

                    if ($row['street'] == null && $row['state'] == null && $row['zipcode'] == null) {
                        $storeAddress->delete();
                        continue;
                    }
                }

                $address = isset($row['address_id']) ? StoreAddress::find($row['address_id']) : new StoreAddress();
                $address->store_id = $store->id;
                $address->street = $row['street'];
                $address->state = $row['state'];
                $address->zipcode = $row['zipcode'];
                $address->save();
            }

            return redirect()->to('admin/stores')->with('success', __('Store has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/stores')->with('error', $exception->getMessage());
        }
    }
}
