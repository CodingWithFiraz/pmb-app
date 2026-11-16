<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            $userTarget = Route::has('applicant.dashboard')
                ? route('applicant.dashboard')
                : route('pendaftar.dashboard');

            return redirect()->intended($userTarget);
        }

        return back()
            ->onlyInput('email')
            ->withErrors(['email' => 'Email atau password salah.']);
    }
}
