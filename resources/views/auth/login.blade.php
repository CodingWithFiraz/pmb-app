@extends('layouts.public')
@section('title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto mt-8">
    <div class="bg-white shadow rounded-xl p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Masuk ke Akun</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember" value="1" checked
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Ingat Saya (Tetap Masuk)
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>
@endsection
