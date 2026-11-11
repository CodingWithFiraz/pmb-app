<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'password' => $validated['password'],
            'role'     => 'pendaftar',
        ]);

        Auth::login($user, remember: true);

        return redirect()->route('pendaftar.dashboard');
    }
}