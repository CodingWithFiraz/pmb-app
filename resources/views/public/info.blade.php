@extends('layouts.app')
@section('title', 'Informasi PMB')

@section('content')
{{-- =========================================================================
     1. SECTION HEADER (Inspired by Watermelon Team Block Editorial Layout)
     ========================================================================= --}}
<div class="mx-auto mb-10 sm:mb-14 flex max-w-3xl flex-col items-center text-center">
    {{-- Status Badge / Label --}}
    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#DBDCFA] bg-white px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-[#5F4AF7] shadow-xs">
        <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
        STATUS PENDAFTARAN
    </div>

    {{-- Prominent Elegant Main Heading --}}
    <h1 class="mb-4 text-3xl font-extrabold tracking-tight text-black sm:text-4xl md:text-5xl leading-[1.18]">
        Pendaftaran Mahasiswa Baru Telah Resmi Dibuka
    </h1>

    {{-- Elegant Description in Muted Slate Gray --}}
    <p class="max-w-2xl text-base sm:text-lg leading-relaxed text-[#818CA2]">
        Temukan linimasa resmi, jadwal batas akhir pendaftaran, dan informasi biaya pendidikan untuk memulai perjalanan akademik Anda di kampus kami.
    </p>
</div>

{{-- =========================================================================
     PERIODS LOOP
     ========================================================================= --}}
@forelse($periods as $period)
    <div class="space-y-6 sm:space-y-8 mb-14 last:mb-0">
        {{-- =====================================================================
             2. ACADEMIC YEAR BANNER (Inspired by Watermelon CTA Block 4 Layout)
             ===================================================================== --}}
        <div class="relative overflow-hidden admin-banner-gradient rounded-3xl p-6 sm:p-8 lg:p-10 text-white shadow-sm border border-white/20">
            {{-- Compact subtle square grid pattern overlay --}}
            <div class="absolute inset-0 banner-grid-pattern-compact pointer-events-none opacity-90"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                {{-- Banner Information Text --}}
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white/90 drop-shadow-xs">
                        <span>Tahun Akademik {{ $period->academic_year }}</span>
                        <span class="text-white/60">•</span>
                        <span>Periode Penerimaan</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight drop-shadow-xs">
                        {{ $period->name }}
                    </h2>

                    <p class="text-sm sm:text-base text-white/90 max-w-xl font-normal drop-shadow-xs leading-relaxed">
                        Jalur seleksi penerimaan reguler dengan kuota terintegrasi ke seluruh pilihan program studi unggulan.
                    </p>
                </div>

                {{-- Banner Right Side Action / Status Pill --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold text-white bg-white/20 backdrop-blur-md border border-white/30 shadow-xs">
                        <span class="w-2 h-2 rounded-full {{ $period->status === 'open' ? 'bg-[#16A34A] animate-pulse' : 'bg-gray-300' }}"></span>
                        {{ $period->status === 'open' ? 'Sedang Dibuka' : 'Ditutup' }}
                    </span>

                    @if($period->status === 'open')
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-black font-bold text-sm shadow-sm hover:bg-gray-100 hover:shadow-md transition">
                            <span>Daftar Sekarang</span>
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- =====================================================================
             3. CARD METRICS FOR DATES & FEES (Inspired by Astrix Dashboard Metrics Layout)
             ===================================================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            {{-- Metric Card 1: Tanggal Buka --}}
            <article class="group relative rounded-2xl bg-white border border-[#DBDCFA] p-5 sm:p-6 shadow-xs hover:border-[#5F4AF7]/40 hover:shadow-sm transition-all duration-200 flex flex-col justify-between gap-6">
                {{-- Top Row: Label paired horizontally with Lucide-style Calendar Icon --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#F2F5FF] border border-[#DBDCFA] text-[#5F4AF7] flex items-center justify-center shrink-0 group-hover:bg-[#5F4AF7] group-hover:text-white transition duration-200">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#818CA2]">
                        Buka
                    </span>
                </div>

                {{-- Bottom Row: Prominent Figure in Bold Typography --}}
                <div class="flex flex-col gap-1">
                    <div class="text-2xl sm:text-3xl font-extrabold text-black font-numeric tracking-tight">
                        {{ $period->starts_at->format('d M Y') }}
                    </div>
                    <span class="text-xs text-[#818CA2]">
                        Awal pengisian formulir pendaftaran
                    </span>
                </div>
            </article>

            {{-- Metric Card 2: Tanggal Tutup --}}
            <article class="group relative rounded-2xl bg-white border border-[#DBDCFA] p-5 sm:p-6 shadow-xs hover:border-[#5F4AF7]/40 hover:shadow-sm transition-all duration-200 flex flex-col justify-between gap-6">
                {{-- Top Row: Label paired horizontally with Lucide-style Clock / Hourglass Icon --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#F2F5FF] border border-[#DBDCFA] text-[#5F4AF7] flex items-center justify-center shrink-0 group-hover:bg-[#5F4AF7] group-hover:text-white transition duration-200">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#818CA2]">
                        Tutup
                    </span>
                </div>

                {{-- Bottom Row: Prominent Figure in Bold Typography --}}
                <div class="flex flex-col gap-1">
                    <div class="text-2xl sm:text-3xl font-extrabold text-black font-numeric tracking-tight">
                        {{ $period->ends_at->format('d M Y') }}
                    </div>
                    <span class="text-xs text-[#818CA2]">
                        Batas akhir pengunggahan dokumen
                    </span>
                </div>
            </article>

            {{-- Metric Card 3: Biaya Pendaftaran --}}
            <article class="group relative rounded-2xl bg-white border border-[#DBDCFA] p-5 sm:p-6 shadow-xs hover:border-[#5F4AF7]/40 hover:shadow-sm transition-all duration-200 flex flex-col justify-between gap-6">
                {{-- Top Row: Label paired horizontally with Lucide-style Tag / Currency Icon --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#F2F5FF] border border-[#DBDCFA] text-[#5F4AF7] flex items-center justify-center shrink-0 group-hover:bg-[#5F4AF7] group-hover:text-white transition duration-200">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#818CA2]">
                        Biaya Pendaftaran
                    </span>
                </div>

                {{-- Bottom Row: Prominent Figure in Bold Typography --}}
                <div class="flex flex-col gap-1">
                    <div class="text-2xl sm:text-3xl font-extrabold text-[#16A34A] font-numeric tracking-tight">
                        {{ $period->registration_fee > 0 ? 'Rp ' . number_format($period->registration_fee, 0, ',', '.') : 'Gratis' }}
                    </div>
                    <span class="text-xs text-[#818CA2]">
                        {{ $period->registration_fee > 0 ? 'Dapat diselesaikan secara online' : 'Bebas biaya seleksi pendaftaran' }}
                    </span>
                </div>
            </article>
        </div>
    </div>
@empty
    {{-- Empty State Card --}}
    <div class="rounded-3xl border border-[#DBDCFA] bg-white p-12 text-center text-[#818CA2] shadow-xs max-w-xl mx-auto">
        <div class="w-14 h-14 rounded-2xl bg-[#F2F5FF] text-[#5F4AF7] border border-[#DBDCFA] flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-black mb-1">Belum Ada Informasi Periode Aktif</h3>
        <p class="text-sm text-[#818CA2]">Jadwal dan rincian pendaftaran gelombang baru akan segera diumumkan.</p>
    </div>
@endforelse
@endsection
