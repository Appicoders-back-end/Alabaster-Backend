<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;
use Stripe\Stripe;

class SubscriptionController extends Controller
{

    public $status = 200;
    public $stripe = null;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    }

    /**
     * @return JsonResponse
     */
    public function getSubscriptionPackages()
    {
        $packages = Subscription::orderBy('created_at', 'DESC')->get();
        $packages = $packages->each(function($package){
            $package->is_subscribed = UserSubscription::where('plan_id', $package->id)->where('user_id', auth()->user()->id)->count() > 0 ? true : false;
        });
        return apiResponse(true, __('Subscription Packages Found'), $packages);
    }

    /**
     * @return JsonResponse
     */
    public function getSubscriptionHistory()
    {
        $user = request()->user();
        $package = UserSubscription::where('user_id', $user->id)->with('plan')->orderBy('created_at', 'DESC')->simplePaginate(1);
        return apiResponse(true, 'User Subscription Packages Found', $package);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required',
            'payment_method_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $user = User::where("id", auth()->user()->id)->first();
        $paymentMethod = PaymentMethod::where(['id' => $request->payment_method_id])->first();
        $plan = Subscription::where(['id' => $request->plan_id])->first();
        if ($paymentMethod == null) {
            return apiResponse(false, __('Payment method not found'));
        }

        if ($plan == null) {
            return apiResponse(false, __('Subscription plan not found'));
        }

        try {
            $subscribe = $this->stripe->subscriptions->create([
                'customer' => getStripeCustomerId($user),
                'items' => [
                    ['price' => $plan->plan_id],
                ],
            ]);

//            $payment = $this->stripe->charges->create([
//                "amount" => 100 * ($plan->price),
//                "currency" => "USD",
//                "source" => $paymentMethod->stripe_source_id,
//                "customer" => getStripeCustomerId($user),
//                "description" => "Membership Booking."
//            ]);

            $packages = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'price' => $plan->price,
                'payment_method_id' => $paymentMethod->id
            ]);

            return apiresponse(true, 'Subscription plan has been subscribed successfully', $plan);
        } catch (\Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }
}
