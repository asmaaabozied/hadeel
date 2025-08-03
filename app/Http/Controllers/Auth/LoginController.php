<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
      public function showLoginForm()
    {
         if (auth()->check()) {
        return redirect()->route('groups.index'); 
    }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Pass "true" to enable "remember me" functionality
        if (Auth::attempt($request->only('email', 'password'), true)) {
            $user = Auth::user();

            if ($user->admin !== 1) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Admins only.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            $request->session()->put('login_time', now());

            return redirect()->intended('/groups');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
