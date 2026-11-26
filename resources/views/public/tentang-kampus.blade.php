@extends('layouts.app')
@section('title', 'Tentang Kampus')
@section('body-class', 'bg-[#F8F6F9]')

@section('full-width-content')
@php
    $pengaturan  = $pengaturan ?? [];
    $fakultas    = $fakultas ?? collect();
    $prodi       = $prodi ?? $fakultas->sum('prodi_count');
    $namaSingkat = $pengaturan['nama_singkat'] ?? 'Kampus';
    $namaKampus  = $pengaturan['nama_kampus'] ?? 'Portal resmi penerimaan mahasiswa baru.';
    $misiList    = ! empty($pengaturan['misi'])
        ? array_values(array_filter(array_map('trim', preg_split('/\R/', $pengaturan['misi']))))
        : [];
@endphp

{{-- ===== HEADER: teks saja tanpa container (background flat) ===== --}}
<section class="w-full bg-[#F8F6F9] pt-12 pb-10 lg:pt-16 lg:pb-12">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
            Tentang {{ $namaSingkat }}
        </h1>
        <p class="hero-enter-delay-1 mt-4 max-w-3xl text-base leading-relaxed text-[#3E465C] sm:text-lg">
            {{ $namaKampus }}
        </p>
    </div>
</section>

{{-- ===== 1. SEJARAH & SAMBUTAN PIMPINAN (background flat) ===== --}}
@if(! empty($pengaturan['sejarah']) || ! empty($pengaturan['sambutan']))
    <section id="profil" class="w-full bg-[#F8F6F9] py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                @if(! empty($pengaturan['sejarah']))
                    <article class="glass-card-jalur rounded-2xl p-7 sm:p-8 {{ ! empty($pengaturan['sambutan']) ? 'lg:col-span-7' : 'lg:col-span-12' }}">
                        <div class="text-sm font-semibold uppercase tracking-wider text-[#3E465C]">Sejarah</div>
                        <h2 class="mt-2 font-heading text-2xl font-bold leading-tight tracking-[-0.02em] text-[#14141F] sm:text-3xl">
                            Perjalanan {{ $namaSingkat }}
                        </h2>
                        <p class="mt-4 text-base leading-relaxed text-[#3E465C]">
                            {{ $pengaturan['sejarah'] }}
                        </p>
                    </article>
                @endif

                @if(! empty($pengaturan['sambutan']))
                    <article class="glass-card-jalur flex flex-col justify-between rounded-2xl p-7 sm:p-8 {{ ! empty($pengaturan['sejarah']) ? 'lg:col-span-5' : 'lg:col-span-12' }}">
                        <div>
                            <div class="text-sm font-semibold uppercase tracking-wider text-[#3E465C]">Sambutan</div>
                            <h2 class="mt-2 font-heading text-2xl font-bold leading-tight tracking-[-0.02em] text-[#14141F] sm:text-3xl">
                                Sambutan Pimpinan
                            </h2>
                            <blockquote class="mt-5 border-l-[3px] border-[#BF8AFC] pl-5 text-base italic leading-relaxed text-[#3E465C]">
                                &ldquo;{{ $pengaturan['sambutan'] }}&rdquo;
                            </blockquote>
                        </div>

                        <div class="mt-6 flex items-center gap-4 border-t border-white/75 pt-5">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] font-heading text-lg font-bold text-white" aria-hidden="true">
                                {{ mb_substr($pengaturan['sambutan_nama'] ?? 'P', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-heading text-base font-bold text-[#14141F]">
                                    {{ $pengaturan['sambutan_nama'] ?? '-' }}
                                </div>
                                @if(! empty($pengaturan['sambutan_jabatan']))
                                    <div class="text-[14px] text-[#3E465C]">
                                        {{ $pengaturan['sambutan_jabatan'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </section>
@endif

{{-- ===== 2. VISI & MISI (background flat) ===== --}}
<section id="visi-misi" class="w-full bg-[#F8F6F9] py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-8 max-w-2xl">
            <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                Visi &amp; Misi
            </h2>
            <p class="mt-3 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Arah pengembangan pendidikan, riset terapan, dan kontribusi bagi industri kreatif nasional.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Visi --}}
            <article class="glass-card-jalur flex flex-col justify-between rounded-2xl p-7 sm:p-8 lg:col-span-5">
                <div>
                    <div class="text-sm font-semibold uppercase tracking-wider text-[#3E465C]">Visi</div>
                    <p class="mt-4 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                        {{ $pengaturan['visi'] ?? '-' }}
                    </p>
                </div>
                <div class="mt-6 border-t border-white/75 pt-4 text-[13px] font-medium text-[#3E465C]">
                    Landasan utama pengembangan akademik {{ $namaSingkat }}
                </div>
            </article>

            {{-- Misi --}}
            <article class="glass-card-jalur rounded-2xl p-7 sm:p-8 lg:col-span-7">
                <div class="text-sm font-semibold uppercase tracking-wider text-[#3E465C]">Misi</div>

                @if(count($misiList) > 0)
                    <ol class="mt-5 divide-y divide-white/75">
                        @foreach($misiList as $index => $poin)
                            <li class="flex items-start gap-4 py-3.5 first:pt-0 last:pb-0">
                                <span class="mt-0.5 inline-flex size-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] font-numeric text-[13px] font-bold text-white">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-base leading-relaxed text-[#3E465C]">{{ $poin }}</span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="mt-4 text-base text-[#3E465C]">-</p>
                @endif
            </article>
        </div>
    </div>
</section>

{{-- ===== 3. FAKULTAS (background flat) ===== --}}
<section id="fakultas" class="w-full bg-[#F8F6F9] py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <h2 class="font-heading text-3xl font-bold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                    Fakultas
                </h2>
                <p class="mt-3 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                    Jelajahi fakultas dan pilihan program studi yang tersedia di {{ $namaSingkat }}.
                </p>
            </div>
            <a href="{{ route('public.fakultas') }}" class="inline-flex min-h-11 sm:min-h-0 items-center gap-1.5 shrink-0 text-base font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                Lihat semua fakultas <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @forelse($fakultas as $f)
                <a href="{{ route('public.fakultas.detail', $f) }}"
                   class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                    <div>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                @if(! empty($f->kode))
                                    <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                        {{ $f->kode }}
                                    </div>
                                @endif
                                <h3 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E] sm:text-2xl">
                                    {{ $f->nama }}
                                </h3>
                            </div>
                            <div class="shrink-0 text-right">
                                <div class="text-sm font-semibold text-[#4E566E]">Prodi</div>
                                <div class="mt-2 font-numeric text-xl font-bold leading-snug tabular-nums text-[#3E465C]">
                                    {{ $f->prodi_count }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 h-[3px] w-14 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" aria-hidden="true"></div>

                        @if(! empty($f->deskripsi))
                            <p class="mt-5 text-base leading-relaxed text-[#3E465C]">
                                {{ $f->deskripsi }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">
                        Lihat program studi <span aria-hidden="true">&rarr;</span>
                    </div>
                </a>
            @empty
                <p class="text-base text-[#3E465C] md:col-span-2">Data fakultas belum tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== 4. AJAKAN PENDAFTARAN & KONTAK KAMPUS (background flat) ===== --}}
<section id="langkah-berikutnya" class="w-full bg-[#F8F6F9] py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="glass-card-jalur rounded-2xl p-7 sm:p-10">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center">
                <div class="space-y-3 lg:col-span-7">
                    <h2 class="font-heading text-2xl font-bold leading-tight tracking-[-0.02em] text-[#14141F] sm:text-3xl">
                        Siap bergabung dengan {{ $namaSingkat }}?
                    </h2>
                    <p class="text-base leading-relaxed text-[#3E465C]">
                        Buat akun pendaftar untuk memilih program studi, atau hubungi panitia PMB jika membutuhkan penjelasan lebih lanjut.
                    </p>
                    @if(! empty($pengaturan['alamat_kampus']))
                        <p class="text-sm text-[#4E566E]">
                            {{ $pengaturan['alamat_kampus'] }}
                        </p>
                    @endif
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:col-span-5 lg:justify-end">
                    <a href="{{ route('register') }}"
                       class="inline-flex h-12 items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 text-base font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#BF8AFC] focus-visible:ring-offset-2">
                        Daftar sekarang
                    </a>
                    <a href="{{ route('public.bantuan.kontak') }}"
                       class="inline-flex h-12 items-center justify-center rounded-xl border border-[#DDE1F5] bg-white px-6 text-base font-semibold text-[#14141F] transition duration-150 hover:-translate-y-0.5 hover:bg-[#F8F9FF] active:translate-y-0">
                        Hubungi panitia
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
