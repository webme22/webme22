<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    public function assure_login(){
        Session::put('admin_auth_token', Auth::user()->createToken('token')->plainTextToken);
        return redirect()->back();
    }
    public function login(Request $request){
        $credentials = $request->only('user_name', 'password');
        $remember = $request->filled('remember_me');
        $user = User::where(['user_name'=>$credentials['user_name']])->where(['role'=>'admin'])->first();
        if($user && password_verify($credentials['password'], $user->user_password)){
            Auth::login($user, $remember);
            $request->session()->regenerate();
            Session::put('admin_auth_token', Auth::user()->createToken('token')->plainTextToken);
            return redirect()->intended(route('admin.get_dashboard'));
        }
//        return $credentials;
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        $tokenId = explode('|', Session::get('admin_auth_token'))[0];
        Auth::user()->tokens()->where('id', $tokenId)->delete();
        Auth::logout();
        Session::remove('admin_auth_token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('admin.get_login'));
    }
}
