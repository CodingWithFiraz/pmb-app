@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto mt-6 sm:mt-10">
    <div class="bg-white shadow-xs border border-[#E5EAFC] rounded-2xl p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-black">Masuk ke Akun</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1.5 text-black">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1.5 text-black">Password</label>
                <input type="password" name="password"
                    class="w-full border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7]"
                    required>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember" value="1" checked
                    class="h-4 w-4 text-[#5F4AF7] focus:ring-[#5F4AF7] border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-[#818CA2]">
                    Ingat Saya (Tetap Masuk)
                </label>
            </div>

            <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-[#5F4AF7] hover:bg-[#4E39E5] rounded-xl shadow-xs transition duration-200">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm mt-6 text-[#818CA2]">
            Belum punya akun? <a href="{{ route('register') }}" class="text-[#5F4AF7] font-semibold hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>
@endsection
