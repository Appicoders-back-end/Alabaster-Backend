<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Plan;
use Stripe\Product;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::get();
        return view('admin.subscriptions', ['subscriptions' => $subscriptions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {

            $product = Product::create([
                'name' => $request->package_name,
                'description' => $request->description
            ]);

            $plan = Plan::create([
                'amount' => $request->price * 100,
                'currency' => 'usd',
                'interval' => $request->interval_time,
                'product' => $product->id,
            ]);

            $subs = new Subscription();
            $subs->package_name = $request->package_name;
            $subs->price = $request->price;
            $subs->interval_time = $request->interval_time;
            $subs->description = $request->description;
            $subs->product_id = $product->id;
            $subs->plan_id = $plan->id;
            $subs->save();

            return redirect()->to('admin/subscriptions')->with('success', __('Plan has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/subscriptions')->with('error', $exception->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        try {
            $subs = Subscription::find($request->id);
            $subs->package_name = $request->package_name;
            $subs->price = $request->price;
            $subs->interval_time = $request->interval_time;
            $subs->description = $request->description;
            $subs->save();

            return redirect()->to('admin/subscriptions')->with('success', __('Plan has been created successfully!'));
        } catch (\Exception $exception) {
            return redirect()->to('admin/subscriptions')->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        $subs = Subscription::where('id', $id)->delete();
        return redirect()->to('admin/subscriptions')->with('success', __('Plan has been deleted successfully!'));
    }
}
