@extends('layouts.app')
@section('title', 'Beranda')

@section('header-class', 'absolute top-0 left-0 right-0 z-40 bg-transparent')
@section('logo-class', 'text-white')
@section('hamburger-class', 'text-white hover:bg-white/10')

@section('full-width-content')

{{-- ===== HERO SECTION (Stepped Typography Layout with Authentic Gradient & Grid + Student Cutout) ===== --}}
<section class="relative w-full overflow-hidden hero-gradient-surface select-none">
    {{-- Subtle white square grid pattern over gradient --}}
    <div class="absolute inset-0 hero-grid-pattern pointer-events-none"></div>

    {{-- Hero Content Container --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative min-h-[580px] sm:min-h-[660px] md:min-h-[720px] lg:min-h-[800px] xl:min-h-[860px] flex flex-col justify-between pt-28 sm:pt-32 md:pt-36 lg:pt-40 pb-10 sm:pb-14 lg:pb-18">
        
        {{-- Giant Typography Stack (Positioned behind student cutout on the left) --}}
        <div class="relative z-10 w-full flex flex-col pointer-events-none">
            {{-- Line 1: Pendaftaran (Top Left / Center) --}}
            <div class="text-left pl-2 sm:pl-8 md:pl-16 lg:pl-20">
                <span class="inline-block text-white font-heading font-black tracking-[-0.04em] leading-[0.88] text-[11.5vw] sm:text-[9.5vw] md:text-[8.5vw] lg:text-[7.6rem] xl:text-[8.8rem] whitespace-nowrap">
                    Pendaftaran
                </span>
            </div>

            {{-- Line 2: Mahasiswa (Middle / Right aligned) --}}
            <div class="text-right pr-2 sm:pr-4 md:pr-8 lg:pr-12 mt-1 sm:mt-2 lg:mt-3">
                <span class="inline-block text-white font-heading font-black tracking-[-0.04em] leading-[0.88] text-[11.5vw] sm:text-[9.5vw] md:text-[8.5vw] lg:text-[7.6rem] xl:text-[8.8rem] whitespace-nowrap">
                    Mahasiswa
                </span>
            </div>

            {{-- Line 3: Baru (Bottom Right aligned) --}}
            <div class="text-right pr-2 sm:pr-4 md:pr-8 lg:pr-12 mt-1 sm:mt-2 lg:mt-3">
                <span class="inline-block text-white font-heading font-black tracking-[-0.04em] leading-[0.88] text-[11.5vw] sm:text-[9.5vw] md:text-[8.5vw] lg:text-[7.6rem] xl:text-[8.8rem] whitespace-nowrap">
                    Baru
                </span>
            </div>
        </div>

        {{-- Bottom-Right Action Buttons (Aligned below 'Baru' on the right side) --}}
        <div class="relative z-30 flex justify-end items-center pr-2 sm:pr-4 md:pr-8 lg:pr-12 mt-6 sm:mt-8 md:mt-10">
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 sm:gap-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center justify-center bg-white text-black font-semibold text-sm px-7 py-3.5 rounded-xl shadow-xs hover:bg-gray-100 transition duration-200">
                            Buka Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('applicant.dashboard') }}"
                           class="inline-flex items-center justify-center bg-white text-black font-semibold text-sm px-7 py-3.5 rounded-xl shadow-xs hover:bg-gray-100 transition duration-200">
                            Buka Dashboard Saya
                        </a>
                    @endif
                    <a href="{{ route('public.info') }}"
                       class="btn-matte-glass inline-flex items-center justify-center font-medium text-sm px-7 py-3.5 rounded-xl transition duration-200">
                        Lihat informasi
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center bg-white text-black font-semibold text-sm px-7 py-3.5 rounded-xl shadow-xs hover:bg-gray-100 transition duration-200">
                        Daftar sekarang
                    </a>
                    <a href="{{ route('public.info') }}"
                       class="btn-matte-glass inline-flex items-center justify-center font-medium text-sm px-7 py-3.5 rounded-xl transition duration-200">
                        Lihat informasi
                    </a>
                @endauth
            </div>
        </div>

        {{-- Students Cutout Image (Positioned at bottom-left in front of text, with bottom fade out) --}}
        <div class="absolute bottom-0 -left-6 sm:-left-10 md:-left-12 lg:-left-14 z-20 pointer-events-none h-[68%] sm:h-[75%] md:h-[80%] lg:h-[85%] max-h-[720px] flex items-end">
            <img src="{{ asset('assets/images/Gambar landing.png') }}"
                 alt="Mahasiswa PMB Online"
                 class="h-full w-auto max-w-[85vw] sm:max-w-[70vw] md:max-w-[58vw] lg:max-w-[52vw] object-contain object-bottom block photo-fade-bottom">
        </div>
    </div>
</section>

{{-- ===== REGISTRATION PROCESS CARDS (3 Steps) ===== --}}
<section class="w-full bg-[#F2F5FF] py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card 1: Isi Formulir --}}
            <div class="bg-white rounded-2xl p-6 border border-[#E5EAFC] shadow-xs flex items-center gap-5 hover:shadow-md transition duration-200">
                <div class="w-14 h-14 rounded-2xl bg-[#F2F5FF] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#5F4AF7]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#000000] leading-snug">Isi Formulir</h3>
                    <p class="text-xs sm:text-sm text-[#818CA2] mt-1 leading-relaxed">
                        Lengkapi data diri dan pendidikan kamu secara online.
                    </p>
                </div>
            </div>

            {{-- Card 2: Unggah dokumen --}}
            <div class="bg-white rounded-2xl p-6 border border-[#E5EAFC] shadow-xs flex items-center gap-5 hover:shadow-md transition duration-200">
                <div class="w-14 h-14 rounded-2xl bg-[#F2F5FF] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#5F4AF7]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#000000] leading-snug">Unggah dokumen</h3>
                    <p class="text-xs sm:text-sm text-[#818CA2] mt-1 leading-relaxed">
                        Upload dokumen persyaratan dalam format PDF atau gambar.
                    </p>
                </div>
            </div>

            {{-- Card 3: Pantau status --}}
            <div class="bg-white rounded-2xl p-6 border border-[#E5EAFC] shadow-xs flex items-center gap-5 hover:shadow-md transition duration-200">
                <div class="w-14 h-14 rounded-2xl bg-[#F2F5FF] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#5F4AF7]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#000000] leading-snug">Pantau status</h3>
                    <p class="text-xs sm:text-sm text-[#818CA2] mt-1 leading-relaxed">
                        Cek proses verifikasi berkas hingga pengumuman hasil seleksi.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
