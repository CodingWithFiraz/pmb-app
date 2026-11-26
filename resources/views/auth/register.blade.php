@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] py-12 lg:py-20">
    <div class="mx-auto w-full max-w-md px-6 sm:px-8">
        <div class="glass-card-jalur hero-enter rounded-2xl p-8 sm:p-10">
            <div class="text-center">
                <div class="font-heading text-xs font-extrabold uppercase tracking-[0.15em] text-[#4E566E]">Portal PNM</div>
                <h1 class="mt-2 font-heading text-2xl font-extrabold tracking-[-0.025em] text-[#14141F] sm:text-3xl">
                    Buat Akun Baru
                </h1>
                <p class="mt-2 text-sm leading-relaxed text-[#3E465C]">
                    Daftar akun pendaftar untuk memilih jalur masuk dan program studi.
                </p>
            </div>

            {{-- Global Validation Errors Alert --}}
            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <div class="mb-1 font-bold">Gagal membuat akun:</div>
                    <ul class="list-inside list-disc space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-7">
                @csrf
                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-[#14141F]">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="w-full rounded-xl border border-[#DDE1F5] bg-white/90 px-4 py-3 text-sm text-[#14141F] placeholder:text-[#818CA2] transition focus:border-[#8923FF] focus:outline-none focus:ring-2 focus:ring-[#8923FF]/20 @error('name') border-rose-500 @enderror"
                        placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-[#14141F]">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-xl border border-[#DDE1F5] bg-white/90 px-4 py-3 text-sm text-[#14141F] placeholder:text-[#818CA2] transition focus:border-[#8923FF] focus:outline-none focus:ring-2 focus:ring-[#8923FF]/20 @error('email') border-rose-500 @enderror"
                        placeholder="nama@email.com" required>
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-[#14141F]">Password</label>
                        <span class="text-xs text-[#4E566E]">Min. 8 karakter</span>
                    </div>
                    <input id="password" type="password" name="password" minlength="8"
                        class="w-full rounded-xl border border-[#DDE1F5] bg-white/90 px-4 py-3 text-sm text-[#14141F] placeholder:text-[#818CA2] transition focus:border-[#8923FF] focus:outline-none focus:ring-2 focus:ring-[#8923FF]/20 @error('password') border-rose-500 @enderror"
                        placeholder="Minimal 8 karakter" required>
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-[#14141F]">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" minlength="8"
                        class="w-full rounded-xl border border-[#DDE1F5] bg-white/90 px-4 py-3 text-sm text-[#14141F] placeholder:text-[#818CA2] transition focus:border-[#8923FF] focus:outline-none focus:ring-2 focus:ring-[#8923FF]/20 @error('password_confirmation') border-rose-500 @enderror"
                        placeholder="Ulangi password di atas" required>
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="inline-flex h-12 w-full cursor-pointer items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 text-base font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#BF8AFC] focus-visible:ring-offset-2">
                    Buat Akun Baru
                </button>
            </form>

            <p class="mt-6 border-t border-white/75 pt-5 text-center text-sm text-[#3E465C]">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</section>
@endsection
