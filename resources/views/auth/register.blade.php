@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="max-w-md mx-auto mt-6 sm:mt-10">
    <div class="bg-white shadow-xs border border-[#E5EAFC] rounded-2xl p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-black">Buat Akun Baru</h2>

        {{-- Global Validation Errors Alert --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <div class="font-bold mb-1">Gagal membuat akun:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            {{-- Nama Lengkap --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1.5 text-black">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1.5 text-black">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-sm font-semibold text-black">Password</label>
                    <span class="text-xs text-[#818CA2]">Min. 8 karakter</span>
                </div>
                <input type="password" name="password"
                    class="w-full border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] @error('password') border-red-500 @enderror"
                    placeholder="Minimal 8 karakter" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-1.5 text-black">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF] @error('password_confirmation') border-red-500 @enderror"
                    placeholder="Ulangi password di atas" required>
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-[#606ADF] hover:bg-[#4F58CC] rounded-xl shadow-xs transition duration-200">
                Buat Akun Baru
            </button>
        </form>

        <p class="text-center text-sm mt-6 text-[#818CA2]">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-[#606ADF] font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection
