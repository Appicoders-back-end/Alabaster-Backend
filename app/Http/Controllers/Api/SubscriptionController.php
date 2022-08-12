<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function getSubscriptionPackages(){

        $packages = Subscription::orderBy('created_at', 'DESC')->get();
        return apiResponse(true, 'Subscription Packages Found', $packages);
    }


    public function getSubscriptionHistory(){

        $user = request()->user();
        $package = UserSubscription::where('user_id', $user->id)->with('plan')->orderBy('created_at', 'DESC')->simplePaginate(1);
        return apiResponse(true, 'User Subscription Packages Found',$package);
    }
}
