<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function customers(Request $request)
    {
        $baseUsers = User::with('addresses', 'company')->where('role', User::Customer);
        $baseUsers->when(request('search'), function ($query) use ($request) {
            return $query->where(function ($where) use ($request) {
                $where->whereHas('company', function ($whereCompany) use ($request) {
                    $whereCompany->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('name', 'like', '%' . $request->search . '%');
            });
        });

        $users = $baseUsers->paginate(10);

        return view('admin.customer.customers-list', ['users' => $users]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function cleaners(Request $request)
    {
        $baseUsers = User::with('category')->where('role', User::Cleaner);
        $baseUsers->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $users = $baseUsers->paginate(10);

        return view('admin.cleaner.cleaners-list', ['users' => $users]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function contractors(Request $request)
    {
        $baseUsers = User::with('addresses')->where('role', User::Contractor);
        $baseUsers->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $users = $baseUsers->paginate(10);

        return view('admin.contractor.contractors-list', ['users' => $users]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => __("User status has been updated successfully")]);
    }

    public function contractorDetail($id)
    {
        $contractor = User::with('addresses', 'contractorCompanies')->find($id);

        return view('admin.contractor.contractor-detail', compact('contractor'));
    }
}
