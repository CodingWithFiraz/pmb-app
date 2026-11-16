<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        $validated = $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'email.unique'        => 'Email ini sudah terdaftar. Silakan masuk memakai email dan password akun tersebut.',
                'password.min'        => 'Password minimal 8 karakter.',
                'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            ]
        );

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'pendaftar',
        ]);

        Auth::login($user, remember: true);

        $target = Route::has('applicant.dashboard')
            ? route('applicant.dashboard')
            : route('pendaftar.dashboard');

        return redirect()->intended($target);
    }
}
