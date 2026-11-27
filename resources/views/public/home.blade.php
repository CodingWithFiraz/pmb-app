@extends('layouts.app')
@section('title', 'Beranda')
@section('body-class', 'bg-[#F8F6F9]')

@section('full-width-content')
@php
    $statusPeriode = $statusPeriode ?? null;
    $activePeriod  = $activePeriod ?? null;
    $pengaturan    = $pengaturan ?? [];
    $fakultas      = $fakultas ?? collect();
    $jalur         = $jalur ?? collect();
    $biaya         = $biaya ?? collect();
    $uktMulai      = $uktMulai ?? collect();
    $berita        = $berita ?? collect();
    $namaSingkat   = $pengaturan['nama_singkat'] ?? 'Politeknik Negeri Media Banten';
    $namaKampus    = $pengaturan['nama_kampus'] ?? 'Politeknik Negeri Media Banten';
    $tagline       = $pengaturan['tagline'] ?? 'Portal resmi penerimaan mahasiswa baru secara daring, transparan, dan terintegrasi.';
    $totalProdi    = $fakultas->sum('prodi_count');
@endphp

{{-- ===== SCREEN 1: HERO (Centered layout) ===== --}}
<section class="relative w-full min-h-[calc(100svh-4rem)] overflow-hidden hero-gradient-surface flex items-center justify-center">
    <div class="relative z-10 w-full max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-24">
        <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
            {{-- Alert status periode: disamakan dengan alert "Sedang dibuka" & kontras tegas di atas gradient --}}
            @php
                $heroAlertStyle = match($statusPeriode) {
                    'buka'  => 'border-emerald-300 bg-emerald-50 text-emerald-800',
                    'belum' => 'border-amber-300 bg-amber-50 text-amber-800',
                    default => 'border-rose-300 bg-rose-50 text-rose-800',
                };
            @endphp
            <div role="alert"
                 class="hero-enter inline-flex w-fit max-w-full items-center gap-2 rounded-xl border px-3.5 py-2 text-sm font-semibold shadow-sm {{ $heroAlertStyle }}">
                <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    @if($statusPeriode === 'buka')
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    @elseif($statusPeriode === 'belum')
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                    @else
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v4a.75.75 0 0 0 1.5 0v-4ZM10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                    @endif
                </svg>
                <span>
                    @if($statusPeriode === 'buka' && $activePeriod)
                        Pendaftaran dibuka &middot; Tahun Akademik {{ $activePeriod->tahun_akademik }}
                    @elseif($statusPeriode === 'belum' && $activePeriod)
                        Pendaftaran dibuka mulai {{ $activePeriod->tanggal_mulai?->translatedFormat('d F Y') }}
                    @elseif($statusPeriode === 'tutup' && $activePeriod)
                        Pendaftaran periode {{ $activePeriod->tahun_akademik }} telah ditutup
                    @else
                        Portal resmi PMB {{ $namaSingkat }}
                    @endif
                </span>
            </div>

            <h1 class="hero-enter-delay-1 mt-6 font-heading text-4xl font-medium leading-[1.08] tracking-[-0.035em] text-black sm:text-5xl lg:text-6xl">
                {{ $namaKampus }}
            </h1>

            <p class="hero-enter-delay-1 mt-5 max-w-2xl text-base leading-relaxed text-black sm:text-lg">
                {{ $tagline }}
            </p>

            {{-- Tombol aksi utama dengan kontras solid di atas gradient --}}
            <div class="hero-enter-delay-2 mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex h-12 items-center justify-center rounded-xl bg-black px-6 text-base font-semibold text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
                            Buka Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('pendaftar.dashboard') }}"
                           class="inline-flex h-12 items-center justify-center rounded-xl bg-black px-6 text-base font-semibold text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
                            Buka Dashboard Saya
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex h-12 items-center justify-center rounded-xl bg-black px-6 text-base font-semibold text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
                        Daftar sekarang
                    </a>
                @endauth

                <a href="{{ route('public.informasi-pmb') }}"
                   class="inline-flex h-12 items-center justify-center rounded-xl border border-[#DDE1F5] bg-white px-6 text-base font-semibold text-black shadow-md transition duration-150 hover:-translate-y-0.5 hover:bg-[#F8F9FF] active:translate-y-0">
                    Lihat informasi PMB
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== 1. KEUNGGULAN (4 kolom terpusat: ikon gradient #BF8AFC -> #FA7F94, judul bold, dan deskripsi singkat) ===== --}}
<section id="keunggulan" class="glass-section-keunggulan w-full border-b border-white/90 py-14 lg:py-20">
    {{-- Definisi SVG Linear Gradient (#BF8AFC -> #FA7F94) untuk keempat ikon keunggulan --}}
    <svg class="sr-only" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="keunggulan-icon-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#BF8AFC" />
                <stop offset="100%" stop-color="#FA7F94" />
            </linearGradient>
        </defs>
    </svg>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-12 text-center lg:mb-14">
            <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                Mengapa Memilih {{ $namaSingkat }}?
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
            {{-- 1. Kurikulum Berbasis Industri --}}
            <div class="flex flex-col items-center text-center">
                <div class="mb-5 flex items-center justify-center" aria-hidden="true">
                    <svg class="size-11" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                        <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0 1 12 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 0 1 7.5 5.455V5.25Zm7.5 0v.09a49.488 49.488 0 0 0-6 0v-.09a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5Zm-3 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        <path d="M3 18.4v-2.796a4.3 4.3 0 0 0 .713.31A26.226 26.226 0 0 0 12 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 0 1-6.477-.427C4.047 21.128 3 19.852 3 18.4Z" />
                    </svg>
                </div>
                <div class="inline-flex max-w-full flex-col items-center">
                    <h3 class="text-balance font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                        Kurikulum Berbasis Industri
                    </h3>
                    <p class="mt-3 w-0 min-w-full px-2 text-base leading-relaxed text-[#3E465C]">
                        Materi dan proyek disusun bersama mitra industri agar lulusan siap masuk dunia kerja.
                    </p>
                </div>
            </div>

            {{-- 2. Dosen Praktisi & Akademisi --}}
            <div class="flex flex-col items-center text-center">
                <div class="mb-5 flex items-center justify-center" aria-hidden="true">
                    <svg class="size-11" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                        <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                        <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                        <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                    </svg>
                </div>
                <div class="inline-flex max-w-full flex-col items-center">
                    <h3 class="text-balance font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                        Dosen Praktisi &amp; Akademisi
                    </h3>
                    <p class="mt-3 w-0 min-w-full px-2 text-base leading-relaxed text-[#3E465C]">
                        Belajar langsung dari dosen berpengalaman serta praktisi aktif yang membawa standar produksi industri ke dalam kelas.
                    </p>
                </div>
            </div>

            {{-- 3. Laboratorium Modern --}}
            <div class="flex flex-col items-center text-center">
                <div class="mb-5 flex items-center justify-center" aria-hidden="true">
                    <svg class="size-11" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                        <path fill-rule="evenodd" d="M2.25 5.25a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3V15a3 3 0 0 1-3 3h-3v.257c0 .597.237 1.17.659 1.591l.621.622a.75.75 0 0 1-.53 1.28h-9a.75.75 0 0 1-.53-1.28l.621-.622a2.25 2.25 0 0 0 .659-1.59V18h-3a3 3 0 0 1-3-3V5.25Zm1.5 0v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="inline-flex max-w-full flex-col items-center">
                    <h3 class="text-balance font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                        Laboratorium Modern
                    </h3>
                    <p class="mt-3 w-0 min-w-full px-2 text-base leading-relaxed text-[#3E465C]">
                        Fasilitas studio dan laboratorium mendukung praktik grafika, desain, animasi, dan multimedia.
                    </p>
                </div>
            </div>

            {{-- 4. Jaringan Mitra Kreatif --}}
            <div class="flex flex-col items-center text-center">
                <div class="mb-5 flex items-center justify-center" aria-hidden="true">
                    <svg class="size-11" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                        <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                        <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                    </svg>
                </div>
                <div class="inline-flex max-w-full flex-col items-center">
                    <h3 class="text-balance font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                        Jaringan Mitra Kreatif
                    </h3>
                    <p class="mt-3 w-0 min-w-full px-2 text-base leading-relaxed text-[#3E465C]">
                        Akses magang, kolaborasi proyek, dan penyaluran kerja melalui jejaring mitra kampus.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== 2. JALUR MASUK ===== --}}
<section id="jalur-masuk" class="w-full bg-[#F8F6F9] py-10 lg:py-12">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                    Jalur Masuk
                </h2>
            </div>
            <a href="{{ route('public.informasi-pmb') }}" class="inline-flex min-h-11 sm:min-h-0 items-center gap-1.5 shrink-0 text-base font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                Lihat informasi lengkap <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($jalur as $j)
                @php
                    $b = $biaya->get($j->id);
                    $minimal = $uktMulai->get($j->id);
                    $gratis = ! ($b && $b->nominal !== null);
                @endphp

                <article class="glass-card-jalur flex flex-col rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                    <h3 class="font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                        {{ $j->nama }}
                    </h3>

                    <p class="mt-3 flex-1 text-base leading-relaxed text-[#3E465C]">
                        {{ $j->deskripsi }}
                    </p>

                    <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-white/75 pt-5">
                        <div>
                            <dt class="text-[13px] font-medium text-[#3E465C]">Biaya daftar</dt>
                            <dd class="mt-1 whitespace-nowrap font-numeric text-xl font-bold tracking-tight tabular-nums {{ $gratis ? 'text-emerald-700' : 'text-[#14141F]' }}">
                                {{ $gratis ? 'Gratis' : 'Rp ' . number_format($b->nominal, 0, ',', '.') }}
                            </dd>
                        </div>
                        @if($minimal !== null)
                            <div>
                                <dt class="text-[13px] font-medium text-[#3E465C]">UKT mulai</dt>
                                <dd class="mt-1 whitespace-nowrap font-numeric text-xl font-bold tracking-tight tabular-nums text-[#14141F]">
                                    Rp {{ number_format($minimal, 0, ',', '.') }}
                                </dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6">
                        <a href="{{ route('register') }}"
                           class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 text-base font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#BF8AFC] focus-visible:ring-offset-2">
                            Daftar jalur {{ $j->kode }}
                        </a>
                    </div>
                </article>
            @empty
                <p class="text-base text-[#3E465C] md:col-span-2 lg:col-span-3">Data jalur masuk belum tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== 3. FAKULTAS & PROGRAM STUDI ===== --}}
<section id="fakultas" class="w-full bg-[#F8F6F9] py-10 lg:py-12">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                    Fakultas &amp; Program Studi
                </h2>
            </div>
            <a href="{{ route('public.fakultas') }}" class="inline-flex min-h-11 sm:min-h-0 items-center gap-1.5 shrink-0 text-base font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                Lihat semua fakultas <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="space-y-12">
            @forelse($fakultas as $f)
                <div>
                    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="text-base font-semibold uppercase tracking-wider text-[#3E465C]">
                                {{ $f->kode }}
                            </div>
                            <h3 class="mt-1 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                                {{ $f->nama }}
                            </h3>
                        </div>
                        <a href="{{ route('public.fakultas.detail', $f) }}"
                           class="inline-flex min-h-11 sm:min-h-0 shrink-0 items-center gap-1.5 text-base font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                            Buka detail fakultas <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    {{-- Semua fakultas memakai grid 3 kolom agar ukuran card seragam --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($f->prodi as $p)
                            <a href="{{ route('public.prodi', $p) }}"
                               class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                                {{ $p->kode }} &middot; {{ $p->jenjang }}
                                            </div>
                                            <h4 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E]">
                                                {{ $p->nama }}
                                            </h4>
                                        </div>
                                        @if($p->kuota)
                                            <div class="shrink-0 text-right">
                                                <div class="text-sm font-semibold text-[#4E566E]">Kuota</div>
                                                <div class="mt-2 font-numeric text-xl font-bold leading-snug tabular-nums text-[#3E465C]">
                                                    {{ $p->kuota }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5 h-[3px] w-14 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" aria-hidden="true"></div>

                                    @if($p->deskripsi)
                                        <p class="mt-5 text-base leading-relaxed text-[#3E465C]">
                                            {{ $p->deskripsi }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-base text-[#3E465C]">Data fakultas belum tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== 4. ALUR PENDAFTARAN LENGKAP ===== --}}
<section id="alur-pendaftaran" class="glass-section-alur w-full border-t border-white/90 py-14 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-10 text-center lg:mb-14">
            <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#111111] sm:text-4xl">
                Alur Pendaftaran Lengkap
            </h2>
        </div>

        @php
            $alurLangkah = [
                ['no' => '01', 'judul' => 'Daftar Akun', 'isi' => 'Buat akun pendaftar menggunakan email aktif dan kata sandi yang aman.'],
                ['no' => '02', 'judul' => 'Lengkapi Data Diri', 'isi' => 'Isi biodata pribadi, riwayat pendidikan, serta data orang tua atau wali.'],
                ['no' => '03', 'judul' => 'Pilih Jalur & Prodi', 'isi' => 'Tentukan jalur masuk dan program studi. Golongan UKT dihitung otomatis.'],
                ['no' => '04', 'judul' => 'Unggah Dokumen', 'isi' => 'Lengkapi berkas persyaratan dalam format PDF atau gambar sesuai jalur.'],
                ['no' => '05', 'judul' => 'Verifikasi & Pembayaran', 'isi' => 'Panitia memverifikasi berkas. Jalur Mandiri menyelesaikan pembayaran.'],
                ['no' => '06', 'judul' => 'Pengumuman Kelulusan', 'isi' => 'Pantau status verifikasi dan hasil seleksi lewat dashboard serta email.'],
            ];
        @endphp

        <ol id="registration-stepper" class="grid grid-cols-1 gap-2 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-12">
            @foreach($alurLangkah as $index => $langkah)
                @php
                    $kolom = $index % 3; // 0 = kiri, 1 = tengah, 2 = kanan (tiap baris: 1-3 dan 4-6)
                @endphp
                <li class="relative">
                    {{-- Konektor vertikal mobile (< lg) --}}
                    @if($index < count($alurLangkah) - 1)
                        <span class="pointer-events-none absolute left-[38px] top-[68px] -bottom-[20px] z-10 w-[4px] rounded-full bg-[#DAD3DF] lg:hidden" aria-hidden="true"></span>
                    @endif

                    {{-- Konektor horizontal desktop: hanya kiri ke kanan dalam satu baris (1-2-3 dan 4-5-6) --}}
                    @if($kolom > 0)
                        <span class="pointer-events-none absolute top-[38px] -left-8 right-[calc(50%+28px)] z-10 hidden h-[4px] bg-[#DAD3DF] lg:block" aria-hidden="true"></span>
                    @endif
                    @if($kolom < 2)
                        <span class="pointer-events-none absolute top-[38px] left-[calc(50%+28px)] -right-8 z-10 hidden h-[4px] bg-[#DAD3DF] lg:block" aria-hidden="true"></span>
                    @endif

                    {{-- Area langkah: highlight glassmorphism saat di-hover --}}
                    <div class="glass-step group relative z-0 flex h-full w-full items-start gap-4 rounded-2xl px-5 pt-5 pb-7 text-left transition duration-200 lg:flex-col lg:items-center lg:gap-0 lg:px-8 lg:text-center">
                        {{-- Tile nomor langkah: gradient #BF8AFC -> #FA7F94 saat di-hover --}}
                        <span class="relative z-20 flex size-10 shrink-0 items-center justify-center rounded-xl border-2 border-[#DAD3DF] bg-white font-numeric text-sm font-bold text-[#3E465C] transition duration-200 group-hover:border-transparent group-hover:bg-gradient-to-r group-hover:from-[#BF8AFC] group-hover:to-[#FA7F94] group-hover:text-white group-hover:ring-4 group-hover:ring-[#BF8AFC]/20">
                            {{ $langkah['no'] }}
                        </span>

                        <div class="relative z-20 lg:mt-5">
                            <h3 class="font-heading text-lg font-bold leading-snug text-[#111111] sm:text-xl">
                                {{ $langkah['judul'] }}
                            </h3>
                            <p class="mt-2.5 text-sm leading-relaxed text-[#3E465C] sm:text-base">
                                {{ $langkah['isi'] }}
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ===== 5. PENDAFTARAN MAHASISWA BARU ===== --}}
{{-- Background flat (tanpa glassmorphism) --}}
<section id="jadwal-penting" class="w-full bg-[#F8F6F9] py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        @if($activePeriod)
            @php
                $jadwalAlertStyle = match($statusPeriode) {
                    'buka'  => 'border-emerald-300 bg-emerald-50 text-emerald-800',
                    'belum' => 'border-amber-300 bg-amber-50 text-amber-800',
                    default => 'border-rose-300 bg-rose-50 text-rose-800',
                };
            @endphp

            <div class="mx-auto flex max-w-2xl flex-col items-center text-center">
                <div role="alert" class="inline-flex w-fit max-w-full items-center gap-2 rounded-xl border px-3.5 py-2 text-sm font-semibold {{ $jadwalAlertStyle }}">
                    <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        @if($statusPeriode === 'buka')
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        @elseif($statusPeriode === 'belum')
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                        @else
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v4a.75.75 0 0 0 1.5 0v-4ZM10 14.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        @endif
                    </svg>
                    <span>
                        @if($statusPeriode === 'buka')
                            Sedang dibuka
                        @elseif($statusPeriode === 'belum')
                            Belum dibuka
                        @else
                            Telah ditutup
                        @endif
                    </span>
                </div>

                <h2 class="mt-5 font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                    Pendaftaran Mahasiswa Baru 2026/2027
                </h2>

                <p class="mt-3 max-w-xl text-base leading-relaxed text-[#3E465C] sm:text-lg">
                    Temukan program studi pilihanmu dan daftarkan diri untuk bergabung bersama kami.
                </p>
            </div>

            {{-- Card tanggal: gradient solid #BF8AFC -> #FA7F94 tanpa glassmorphism / glass edges --}}
            <dl class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="glass-card-gradient rounded-2xl p-7 sm:p-8">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-white/90">
                        Pendaftaran Dibuka
                    </dt>
                    <dd class="mt-2 font-numeric text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        {{ $activePeriod->tanggal_mulai?->translatedFormat('d F Y') }}
                    </dd>
                </div>

                <div class="glass-card-gradient glass-card-gradient--alt rounded-2xl p-7 sm:p-8">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-white/90">
                        Pendaftaran Ditutup
                    </dt>
                    <dd class="mt-2 font-numeric text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        {{ $activePeriod->tanggal_selesai?->translatedFormat('d F Y') }}
                    </dd>
                </div>
            </dl>

            <div class="mt-10 flex justify-center">
                <a href="{{ route('public.informasi-pmb') }}"
                   class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-black px-6 text-base font-semibold text-white transition duration-150 hover:bg-[#14141F]/85 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black focus-visible:ring-offset-2">
                    <span>Lihat Informasi PMB</span>
                    <svg class="size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        @else
            <div class="glass-card-jalur rounded-2xl p-7 text-center text-base text-[#3E465C]">
                Saat ini belum ada periode pendaftaran yang aktif. Pantau halaman berita untuk informasi terbaru.
            </div>
        @endif
    </div>
</section>

{{-- ===== 6. BERITA TERBARU ===== --}}
<section id="berita" class="glass-section-berita w-full pt-14 pb-10 lg:pt-20 lg:pb-12">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                    Berita Terbaru
                </h2>
            </div>
            <a href="{{ route('public.berita') }}" class="inline-flex min-h-11 sm:min-h-0 items-center gap-1.5 shrink-0 text-base font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                Lihat semua berita <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @forelse($berita as $item)
                <a href="{{ route('public.berita.detail', $item) }}"
                   class="glass-card-jalur group flex flex-col overflow-hidden rounded-2xl transition duration-200 hover:-translate-y-0.5">
                    @if($item->image_url)
                        <div class="h-48 w-full overflow-hidden bg-[#F1EFF3]">
                            <img src="{{ $item->image_url }}" alt="{{ $item->judul }}"
                                 loading="lazy" decoding="async"
                                 onload="this.classList.add('is-loaded')"
                                 class="img-blur-up h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        </div>
                    @else
                        <div class="flex h-36 items-end bg-gradient-to-br from-[#BF8AFC] to-[#FA7F94] p-6 text-white">
                            <span class="text-[13px] font-semibold text-white/95">{{ $item->author }}</span>
                        </div>
                    @endif

                    <div class="flex flex-1 flex-col p-6">
                        <div class="text-[13px] font-medium text-[#3E465C]">
                            {{ $item->published_at?->translatedFormat('d F Y') }}
                        </div>
                        <h3 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] group-hover:text-[#4E566E] transition">
                            {{ $item->judul }}
                        </h3>
                        <p class="mt-3 flex-1 text-base leading-relaxed text-[#3E465C]">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi, 120) }}
                        </p>
                        <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">
                            Baca berita <span aria-hidden="true">&rarr;</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-base text-[#3E465C] md:col-span-3">Belum ada berita yang dipublikasikan.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== 7. BANTUAN CEPAT ===== --}}
<section id="bantuan" class="glass-section-bantuan w-full pt-10 pb-14 lg:pt-12 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-8 max-w-2xl">
            <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                Bantuan Cepat
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <a href="{{ route('public.bantuan.panduan') }}"
               class="glass-card-jalur group flex flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                <div>
                    <div class="mb-4 inline-flex size-11 items-center justify-center rounded-xl bg-white/80 ring-1 ring-white" aria-hidden="true">
                        <svg class="size-6" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                            <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold tracking-tight text-[#14141F] group-hover:text-[#4E566E] transition">
                        Panduan Pendaftaran
                    </h3>
                    <p class="mt-2.5 text-base leading-relaxed text-[#3E465C]">
                        Langkah demi langkah membuat akun, mengisi data diri, hingga mengunggah dokumen.
                    </p>
                </div>
                <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">Buka panduan <span aria-hidden="true">&rarr;</span></div>
            </a>

            <a href="{{ route('public.bantuan.faq') }}"
               class="glass-card-jalur group flex flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                <div>
                    <div class="mb-4 inline-flex size-11 items-center justify-center rounded-xl bg-white/80 ring-1 ring-white" aria-hidden="true">
                        <svg class="size-6" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 0 1-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 0 1-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 0 1-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584ZM12 18a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold tracking-tight text-[#14141F] group-hover:text-[#4E566E] transition">
                        Pertanyaan Umum
                    </h3>
                    <p class="mt-2.5 text-base leading-relaxed text-[#3E465C]">
                        Jawaban atas pertanyaan yang paling sering diajukan oleh calon mahasiswa baru.
                    </p>
                </div>
                <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">Lihat FAQ <span aria-hidden="true">&rarr;</span></div>
            </a>

            <a href="{{ route('public.bantuan.kontak') }}"
               class="glass-card-jalur group flex flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                <div>
                    <div class="mb-4 inline-flex size-11 items-center justify-center rounded-xl bg-white/80 ring-1 ring-white" aria-hidden="true">
                        <svg class="size-6" viewBox="0 0 24 24" fill="url(#keunggulan-icon-gradient)">
                            <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0 1 12 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 0 1-3.476.383.39.39 0 0 0-.297.17l-2.755 4.133a.75.75 0 0 1-1.248 0l-2.755-4.133a.39.39 0 0 0-.297-.17 48.9 48.9 0 0 1-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold tracking-tight text-[#14141F] group-hover:text-[#4E566E] transition">
                        Hubungi Panitia
                    </h3>
                    <p class="mt-2.5 text-base leading-relaxed text-[#3E465C]">
                        Kontak resmi panitia PMB, alamat kampus, jam layanan, dan peta lokasi.
                    </p>
                </div>
                <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">Buka kontak <span aria-hidden="true">&rarr;</span></div>
            </a>
        </div>
    </div>
</section>
@endsection
