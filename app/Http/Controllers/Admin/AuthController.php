<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\User;
use App\Models\Admin;
use Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
    	return view('auth.admin_login');
    }

    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;
        if ($request->has('remember') && $request->remember != 0) {
            $remember = 1;
        }else{
            $remember = 0;
        }
        // if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password, 'role' => 'admin'], $remember)) {
    	if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password ], $remember)) {
    		return redirect('/dashboard');
        }elseif (Auth::guard('company')->attempt(['email' => $email, 'password' => $password, 'active' => 1 ], $remember)) {
    		return redirect('/company');
        }else{
            return redirect()->back()->with('error', 'wrong credentals');
        }
    }

    public function logout(Request $request)
    {
            auth()->logout();
            Auth::logout();
        if ($request->has('dashboard')) {
            return redirect(url('/admin/login'));
        }
        return redirect('/');
    }
}
