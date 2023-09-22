<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('login');
    }

    public function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // Cek apakah user active
            if (Auth::user()->status != 'active') {
                Session::flash('status', 'failed');
                Session::flash('message', 'Your account is not acitve yet. Please contact admin!');
                return redirect('login');
            }

            $request->session()->regenerate();
            if (Auth::user()->role_id == 1) {
                return redirect('dashboard');
            } else {
                return redirect('profile');
            }


            //return redirect();
        }

        Session::flash('status', 'failed');
        Session::flash('message', 'Login invalid!');

        return redirect('login');
    }
}
