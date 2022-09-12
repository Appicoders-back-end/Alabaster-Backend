<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return redirect()->back()->withInput();;//->with('error', __('Invalid Credentials'));
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
}
