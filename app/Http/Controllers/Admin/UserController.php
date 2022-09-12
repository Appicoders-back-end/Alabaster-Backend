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
        $baseUsers = User::with('addresses')->where('role', User::Customer);
        $baseUsers->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
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
}
