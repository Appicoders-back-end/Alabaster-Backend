<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Page;
use App\Models\UserSubscription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function contactQueries(Request $request)
    {
        $baseQueries = ContactUs::with('user');
        $baseQueries->when($request->search, function ($query) use ($request) {
            return $query->where('title', 'like', '%' . $request->search . '%')->orWhere('message', 'like', '%' . $request->search . '%')->orWhereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('name', 'like', '%' . $request->search . '%')->orWhere('role', 'like', '%' . $request->search . '%');
            });
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

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $category = new Category();
            $category->name = $request->name;
            if ($request->file('image')) {
                $category->image = saveFile($request->file('image'));
            }
            $category->save();

            return redirect()->to('admin/categories')->with('success', __('Category has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/categories')->with('error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $request->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $category = Category::find($request->id);
            $category->name = $request->name;
            if ($request->file('image')) {
                $category->image = saveFile($request->file('image'));
            }
            $category->save();

            return redirect()->to('admin/categories')->with('success', __('Category has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/categories')->with('error', $exception->getMessage());
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function terms()
    {
        $page = Page::firstOrCreate();
        return view('admin.pages.terms', ['page' => $page]);
    }

    /**
     * @return Application|Factory|View
     */
    public function privacy()
    {
        $page = Page::firstOrCreate();
        return view('admin.pages.privacy', ['page' => $page]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePage(Request $request)
    {
        try {
            Page::where('id', $request->id)->update([$request->page => $request->data]);

            return redirect()->back()->with('success', __('Page has been updated successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function payments(Request $request)
    {
        $payments = UserSubscription::paginate(10);
        return view('admin.payments', ['payments' => $payments]);
    }
}
