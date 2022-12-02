<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function login(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('admin.customers');
        }
        return view('admin.login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            session()->flash('error', __('Invalid Credentials'));
            return redirect()->back()->withInput()->with('error', __('Invalid Credentials'));
        }
        Auth::loginUsingId(auth()->user()->id);
        return redirect()->route('admin.customers');
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    /**
     * @return Application|Factory|View
     */
    public function changePassword()
    {
        return view('admin.change-password');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'new_password' => ['required', 'min:8'],
            'confirm_new_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $user = auth()->user();
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('admin.changePassword')->with('success', __('Password has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.changePassword')->with('error', $exception->getMessage());
        }
    }
}
