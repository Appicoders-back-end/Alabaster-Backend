<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Subscription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function subscriptions()
    {
        $subscriptions = Subscription::get();
        return view('admin.subscriptions', ['subscriptions' => $subscriptions]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function contactQueries(Request $request)
    {
        $baseQueries = ContactUs::with('user');
        $baseQueries->when($request->search, function ($query) use ($request) {
            return $query->where('title', 'like', '%' . $request->search . '%')->orWhere('message', 'like', '%' . $request->search . '%');
        });

        $queries = $baseQueries->paginate(10);

        return view('admin.contact-queries', ['queries' => $queries]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function categories(Request $request)
    {
        $baseCategories = Category::query();
        $baseCategories->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        $categories = $baseCategories->get();
        return view('admin.categories', ['categories' => $categories]);
    }
}
