@extends('layouts.app')
@section('title', 'Masuk')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] py-12 lg:py-20">
    <div class="mx-auto w-full max-w-md px-6 sm:px-8">
        <div class="glass-card-jalur hero-enter rounded-2xl p-8 sm:p-10">
            <div class="text-center">
                <div class="text-xs font-semibold uppercase tracking-wider text-[#4E566E]">Portal PMB Online</div>
                <h1 class="mt-2 font-heading text-2xl font-extrabold tracking-[-0.025em] text-[#14141F] sm:text-3xl">
                    Masuk ke Akun
                </h1>
                <p class="mt-2 text-sm leading-relaxed text-[#3E465C]">
                    Masuk untuk melanjutkan proses pendaftaran mahasiswa baru.
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-7">
                @csrf
                <div class="mb-4">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-[#14141F]">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        class="w-full rounded-xl border border-[#DDE1F5] bg-white/90 px-4 py-3 text-sm text-[#14141F] placeholder:text-[#818CA2] transition focus:border-[#8923FF] focus:outline-none focus:ring-2 focus:ring-[#8923FF]/20 @error('email') border-rose-500 @enderror"
                        required>
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-[#14141F]">Password</label>
                    <input id="password" type="password" name="password"
                        placeholder="Masukkan password Anda"
                        class="w-full rounded-xl border border-[#DDE1F5] bg-white/90 px-4 py-3 text-sm text-[#14141F] placeholder:text-[#818CA2] transition focus:border-[#8923FF] focus:outline-none focus:ring-2 focus:ring-[#8923FF]/20"
                        required>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="remember" id="remember" value="1" checked
                        class="size-4 rounded border-[#DDE1F5] accent-[#8923FF] focus:ring-[#8923FF]/30">
                    <label for="remember" class="ml-2 block text-sm text-[#3E465C]">
                        Ingat Saya (Tetap Masuk)
                    </label>
                </div>

                <button type="submit"
                    class="inline-flex h-12 w-full cursor-pointer items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 text-base font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#BF8AFC] focus-visible:ring-offset-2">
                    Masuk
                </button>
            </form>

            <p class="mt-6 border-t border-white/75 pt-5 text-center text-sm text-[#3E465C]">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</section>
@endsection
