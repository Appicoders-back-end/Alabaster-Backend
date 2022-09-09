<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

//        $request->validate([
//            'email' => 'required|email|unique:users,email',
//            'password' => 'required',
//        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            session()->flash('error', __('Invalid Credentials'));
            return redirect()->back()->withInput();;//->with('error', __('Invalid Credentials'));
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
