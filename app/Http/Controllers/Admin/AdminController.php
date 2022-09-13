<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
