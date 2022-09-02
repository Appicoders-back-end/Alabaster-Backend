<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:8'],
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {

            $stripe = new StripeClient(env("STRIPE_SECRET_KEY"));
            $stripeCustomer = $stripe->customers->create([

                'email' => $request->email,
                'name' => $request->username,
            ]);

            $user = new user();
            $user['stripe_customer_id'] = $stripeCustomer->id;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = User::Contractor;

            if (!$user->save()) {
                return apiResponse(false, __('Something went wrong'));
            }
            $user->markEmailAsVerified(true); //todo will be committed after signup process completed
            $user->token = $user->createToken('MyAuthToken')->accessToken;

            return apiResponse(true, __('User has been created successfully'), $user);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $checkUser = User::where('email', $request->email)->where('status', User::Active)->first();

        if (!$checkUser) {
            return apiResponse(false, __('Your account is inactive, contact to admin.'));
        }

        if ($checkUser->email_verified_at == null) {
            return apiResponse(false, __('Please verify your account'));
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return apiResponse(false, __("Invalid Credentials"));
        }

        $user = User::where('id', auth()->user()->id)->first();
        $user->is_online = '1';
        $user->device_id = $request->device_id;
        $user->save();
        $user->token = $user->createToken('MyAuthToken')->accessToken;
        $user->addresses;

        broadcast(new \App\Events\OnlineStatus($user))->toOthers();
        return apiResponse(true, __('Logged in successfully'), $user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $user = User::where('email', $request->email)->first();

        $code = rand(1111, 9999);
        $user->remember_token = $code;
        $user->save();
        Mail::to($request->email)->send(new ForgotPassword($user->name, $code));

        $data = [
            'email' => $user->email,
            'code' => $code
        ];

        return apiresponse(true, __('Email sent successfully'), $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyForgotCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $user = User::where('email', $request->email)->where('remember_token', $request->code)->first();
        if (!$user) {
            return apiResponse(false, __('Invalid code'));
        }

        $user->remember_token = null;
        $user->save();
//        $user->markEmailAsVerified(true);

        return apiResponse(true, 'Code matched successfully', $user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'min:8'],
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->remember_token = null;
//            $user->save();
//            $user->markEmailAsVerified(true);

            return apiResponse(true, __('Password has been changed successfully'));
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }


    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'old_password'          =>          'required_with:password|min:8',
            'new_password'          =>          'min:8|required_with:confirm_password|same:confirm_password|different:old_password',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $old_password   =   Hash::check($request->old_password, Auth::User()->password);
            if ($old_password) {
                $data['password']       =   Hash::make($request->new_password);
                $user = User::findOrFail(auth()->user()->id)->update($data);
                if ($user) {
                    return apiresponse(true, 'Password has been updated successfully', $data);
                } else {
                    return apiresponse(false, 'Error occurred, please try again');
                }
            } else {
                return apiresponse(false, "Old password is incorrect");
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        $user = request()->user();
        User::findOrFail($user->id)->update(['device_id' => null, 'is_online' => '0']);
        return apiresponse(true, 'You have been logged out successfully');
    }
}
