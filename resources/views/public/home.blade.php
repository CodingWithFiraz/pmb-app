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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative min-h-[580px] sm:min-h-[660px] md:min-h-[740px] lg:min-h-[820px] xl:min-h-[880px] flex flex-col justify-start pt-28 sm:pt-32 md:pt-36 lg:pt-40 pb-36 sm:pb-40 md:pb-48 lg:pb-56">
        
        {{-- Giant Typography Stack (Positioned behind student cutout on the left) --}}
        <div class="relative z-10 w-full flex flex-col pointer-events-none">
            {{-- Line 1: Penerimaan (Top Left / Center) --}}
            <div class="text-left pl-2 sm:pl-8 md:pl-16 lg:pl-20">
                <span class="inline-block text-white font-heading font-black tracking-[-0.04em] leading-[0.88] text-[11.5vw] sm:text-[9.5vw] md:text-[8.5vw] lg:text-[7.6rem] xl:text-[8.8rem] whitespace-nowrap">
                    Penerimaan
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

            {{-- CTA Buttons: Positioned higher, directly below 'Baru' with comfortable spacing --}}
            <div class="flex justify-end pr-2 sm:pr-4 md:pr-8 lg:pr-12 mt-5 sm:mt-6 md:mt-8">
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 sm:gap-4 pointer-events-auto">
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
        </div>

        {{-- Students illustration (bottom-left, in front of the hero type) --}}
        <div class="absolute bottom-0 -left-6 sm:-left-10 md:-left-12 lg:-left-14 z-20 pointer-events-none h-[68%] sm:h-[75%] md:h-[80%] lg:h-[85%] max-h-[720px] flex items-end">
            <img src="{{ asset('assets/images/ilustrasi_untuk_hero.png') }}"
                 alt="Ilustrasi mahasiswa PMB Online"
                 class="h-full w-auto max-w-[85vw] sm:max-w-[70vw] md:max-w-[58vw] lg:max-w-[52vw] object-contain object-bottom block">
        </div>
    </div>
</section>

{{-- ===== FLOATING PROCESS CARDS (Overlapping Boundary between Hero Gradient & Light Gray Section) ===== --}}
<section class="relative w-full bg-[#F2F5FF] pt-0 pb-16 sm:pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 -mt-16 sm:-mt-20 md:-mt-24 relative z-30">
        
        {{-- Unified 3-Column White Card Container (Matching Astrix / Info Page Style) --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-[#DBDCFA] shadow-xl shadow-[#5F4AF7]/6 overflow-hidden grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#E5EAFC]">

            {{-- Kolom 1: Isi Formulir --}}
            <div class="p-6 sm:p-8 lg:p-9 flex flex-col gap-4 group hover:bg-[#F9FAFF] transition duration-200 min-w-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#F2F5FF] border border-[#DBDCFA] text-[#5F4AF7] flex items-center justify-center shrink-0 group-hover:bg-[#5F4AF7] group-hover:text-white transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </div>
                    <h3 class="process-card-title font-extrabold font-heading text-black tracking-tight min-w-0">
                        Isi Formulir
                    </h3>
                </div>
                <p class="process-card-desc text-[#818CA2] font-normal">
                    Lengkapi data diri dan pendidikan kamu secara online.
                </p>
            </div>

            {{-- Kolom 2: Unggah Dokumen --}}
            <div class="p-6 sm:p-8 lg:p-9 flex flex-col gap-4 group hover:bg-[#F9FAFF] transition duration-200 min-w-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#F2F5FF] border border-[#DBDCFA] text-[#5F4AF7] flex items-center justify-center shrink-0 group-hover:bg-[#5F4AF7] group-hover:text-white transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <h3 class="process-card-title font-extrabold font-heading text-black tracking-tight min-w-0">
                        Unggah Dokumen
                    </h3>
                </div>
                <p class="process-card-desc text-[#818CA2] font-normal">
                    Upload dokumen persyaratan dalam format PDF atau gambar.
                </p>
            </div>

            {{-- Kolom 3: Pantau Status --}}
            <div class="p-6 sm:p-8 lg:p-9 flex flex-col gap-4 group hover:bg-[#F9FAFF] transition duration-200 min-w-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#F2F5FF] border border-[#DBDCFA] text-[#5F4AF7] flex items-center justify-center shrink-0 group-hover:bg-[#5F4AF7] group-hover:text-white transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </div>
                    <h3 class="process-card-title font-extrabold font-heading text-black tracking-tight min-w-0">
                        Pantau Status
                    </h3>
                </div>
                <p class="process-card-desc text-[#818CA2] font-normal">
                    Cek proses verifikasi berkas hingga pengumuman hasil seleksi.
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
