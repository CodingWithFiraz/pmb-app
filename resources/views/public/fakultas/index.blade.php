@extends('layouts.app')
@section('title', 'Fakultas')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="mb-10 max-w-2xl">
            <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                Fakultas
            </h1>
            <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Daftar fakultas yang menaungi seluruh program studi di {{ $pengaturan['nama_singkat'] ?? 'Politeknik Negeri Media Banten' }}.
            </p>
        </div>

        {{-- Card mengikuti desain card section "Fakultas & Program Studi" di halaman utama --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($fakultas as $f)
                <a href="{{ route('public.fakultas.detail', $f) }}"
                   class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                    <div>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                @if($f->kode)
                                    <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                        {{ $f->kode }}
                                    </div>
                                @endif
                                <h2 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E]">
                                    {{ $f->nama }}
                                </h2>
                            </div>
                            <div class="shrink-0 text-right">
                                <div class="text-sm font-semibold text-[#4E566E]">Prodi</div>
                                <div class="mt-2 font-numeric text-xl font-bold leading-snug tabular-nums text-[#3E465C]">
                                    {{ $f->prodi_count }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 h-[3px] w-14 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" aria-hidden="true"></div>

                        @if($f->deskripsi)
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
                <p class="text-base text-[#3E465C] md:col-span-2 lg:col-span-3">Belum ada data fakultas.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
