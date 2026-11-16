@extends('layouts.app')
@section('title', 'Beranda')

@section('full-width-content')

{{-- ===== HERO SECTION (Matching Image 1 Layout + Image 2 Soft Gradient & Grid) ===== --}}
<section class="relative w-full overflow-hidden hero-gradient-surface">
    {{-- Subtle white square grid pattern over gradient --}}
    <div class="absolute inset-0 hero-grid-pattern pointer-events-none"></div>

    {{-- Hero Content Container --}}
    <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-20 sm:py-24 lg:py-28">
        <div class="max-w-2xl text-left">
            {{-- Main Heading: #FFFFFF --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#FFFFFF] tracking-tight leading-[1.15]">
                Penerimaan<br>Mahasiswa Baru
            </h1>

            {{-- Supporting Description: #E4E7FF --}}
            <p class="text-[#E4E7FF] text-base sm:text-lg mt-5 font-normal leading-relaxed">
                Daftarkan dirimu dan mulai perjalanan akademikmu bersama kami — dari pendaftaran hingga penerimaan.
            </p>

            {{-- Registration Information (Date, Year, Free / Fee) --}}
            @if($activePeriod)
                <div class="flex flex-wrap items-center gap-2.5 text-sm font-medium text-[#E4E7FF] mt-6">
                    <svg class="w-4 h-4 text-[#E4E7FF] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>{{ $activePeriod->starts_at->format('d M Y') }} — {{ $activePeriod->ends_at->format('d M Y') }}</span>
                    <span class="text-[#E4E7FF]/60">•</span>
                    @if($activePeriod->registration_fee > 0)
                        <span>Biaya: Rp {{ number_format($activePeriod->registration_fee, 0, ',', '.') }}</span>
                    @else
                        <span>Gratis</span>
                    @endif
                </div>
            @else
                <div class="flex items-center gap-2 text-sm text-[#E4E7FF] mt-6">
                    <svg class="w-4 h-4 text-[#E4E7FF] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                    <span>Saat ini belum ada periode pendaftaran aktif. Pantau terus informasi kami.</span>
                </div>
            @endif

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap items-center gap-4 mt-8">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center justify-center bg-white text-[#000000] font-semibold text-sm px-7 py-3 rounded-xl shadow-xs hover:bg-gray-100 transition duration-200">
                            Buka Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('applicant.dashboard') }}"
                           class="inline-flex items-center justify-center bg-white text-[#000000] font-semibold text-sm px-7 py-3 rounded-xl shadow-xs hover:bg-gray-100 transition duration-200">
                            Buka Dashboard Saya
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center bg-white text-[#000000] font-semibold text-sm px-7 py-3 rounded-xl shadow-xs hover:bg-gray-100 transition duration-200">
                        Daftar sekarang
                    </a>
                @endauth

                {{-- Secondary Button: Matte Glass (Adapts to gradient behind it) --}}
                <a href="{{ route('public.info') }}"
                   class="btn-matte-glass inline-flex items-center justify-center font-medium text-sm px-7 py-3 rounded-xl transition duration-200">
                    Lihat informasi
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== REGISTRATION INFORMATION CARDS (Matching Image 1) ===== --}}
<section class="w-full bg-[#F2F5FF] py-10 sm:py-12">
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
                        Cek perkembangan pendaftaran kamu kapan saja.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
