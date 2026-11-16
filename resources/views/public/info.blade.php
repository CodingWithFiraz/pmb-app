@extends('layouts.app')
@section('title', 'Informasi PMB')

@section('content')
@forelse($periods as $period)
    <div class="mb-14 last:mb-0">
        {{-- Section Header --}}
        <div class="mx-auto mb-10 sm:mb-12 flex max-w-3xl flex-col items-center text-center">
            {{-- PMB Period Name: Lebih kecil dari judul, Abu-abu Gelap (#374151 / text-gray-700), ALL CAPS --}}
            <span class="text-xs sm:text-sm font-bold uppercase tracking-widest text-gray-700 font-heading mb-2.5">
                {{ $period->name }}
            </span>

            {{-- Prominent Elegant Main Heading --}}
            <h1 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl md:text-5xl leading-[1.18]">
                Pendaftaran Mahasiswa Baru Telah Resmi Dibuka
            </h1>
        </div>

        {{-- 3-Column Card Metrics: Buka, Tutup, Biaya Pendaftaran (Astrix Style) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            {{-- Metric Card 1: Tanggal Buka --}}
            <article class="group relative rounded-2xl bg-white border border-[#DBDCFA] p-5 sm:p-6 shadow-xs hover:border-[#5F4AF7]/40 hover:shadow-sm transition-all duration-200 flex flex-col justify-between gap-6">
                {{-- Top Row: Label paired horizontally with Calendar Icon --}}
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
                {{-- Top Row: Label paired horizontally with Clock Icon --}}
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
                {{-- Top Row: Label paired horizontally with Tag Icon --}}
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
