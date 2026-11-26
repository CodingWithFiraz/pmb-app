@extends('layouts.app')
@section('title', $fakultas->nama)
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-10 pb-14 lg:pt-12 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="mb-8 text-sm text-[#4E566E]">
            <a href="{{ route('home') }}" class="hover:text-[#14141F] transition">Beranda</a>
            <span class="mx-1.5" aria-hidden="true">/</span>
            <a href="{{ route('public.fakultas') }}" class="hover:text-[#14141F] transition">Fakultas</a>
            <span class="mx-1.5" aria-hidden="true">/</span>
            <span class="font-medium text-[#14141F]">{{ $fakultas->nama }}</span>
        </nav>

        {{-- Header fakultas: teks tanpa container --}}
        <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                @if($fakultas->kode)
                    <div class="hero-enter text-base font-semibold uppercase tracking-wider text-[#3E465C]">
                        {{ $fakultas->kode }}
                    </div>
                @endif
                <h1 class="hero-enter mt-2 font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                    {{ $fakultas->nama }}
                </h1>
                @if($fakultas->deskripsi)
                    <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                        {{ $fakultas->deskripsi }}
                    </p>
                @endif
            </div>
            <div class="hero-enter-delay-1 shrink-0 sm:text-right">
                <div class="text-sm font-semibold text-[#4E566E]">Program Studi</div>
                <div class="mt-1 font-numeric text-3xl font-bold tabular-nums text-[#14141F]">
                    {{ $fakultas->prodi->count() }}
                </div>
            </div>
        </div>

        <h2 class="mb-6 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
            Program Studi
        </h2>

        {{-- Card mengikuti desain card section "Fakultas & Program Studi" di halaman utama --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($fakultas->prodi as $prodi)
                <a href="{{ route('public.prodi', $prodi) }}"
                   class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                    <div>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                    {{ $prodi->kode }} &middot; {{ $prodi->jenjang }}
                                </div>
                                <h3 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#14141F]">
                                    {{ $prodi->nama }}
                                </h3>
                            </div>
                            @if($prodi->kuota)
                                <div class="shrink-0 text-right">
                                    <div class="text-sm font-semibold text-[#4E566E]">Kuota</div>
                                    <div class="mt-2 font-numeric text-xl font-bold leading-snug tabular-nums text-[#3E465C]">
                                        {{ $prodi->kuota }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 h-[3px] w-14 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" aria-hidden="true"></div>

                        @if($prodi->deskripsi)
                            <p class="mt-5 text-base leading-relaxed text-[#3E465C]">
                                {{ $prodi->deskripsi }}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-base text-[#3E465C] md:col-span-2 lg:col-span-3">Belum ada program studi pada fakultas ini.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
