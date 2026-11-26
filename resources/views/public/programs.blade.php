@extends('layouts.app')
@section('title', 'Program Studi')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Header halaman: teks tanpa container --}}
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                    Program Studi
                </h1>
                <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                    Pilihan program studi yang tersedia beserta informasi kuota penerimaan.
                </p>
            </div>
            <a href="{{ route('public.fakultas') }}"
               class="inline-flex min-h-11 sm:min-h-0 shrink-0 items-center gap-1.5 text-base font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                Lihat berdasarkan fakultas <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        {{-- Programs Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($programs as $program)
                @php
                    $programNama      = $program->nama ?? $program->name ?? 'Program Studi';
                    $programFakultas  = $program->fakultas?->nama ?? $program->faculty ?? null;
                    $programKuota     = $program->kuota ?? $program->quota ?? null;
                    $programDeskripsi = $program->deskripsi ?? $program->description ?? null;
                @endphp

                <a href="{{ route('public.prodi', $program) }}"
                   class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                    <div>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                @if(! empty($program->kode) || ! empty($program->jenjang))
                                    <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                        {{ $program->kode }}@if(! empty($program->kode) && ! empty($program->jenjang)) &middot; @endif{{ $program->jenjang }}
                                    </div>
                                @endif
                                <h2 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E]">
                                    {{ $programNama }}
                                </h2>
                                @if($programFakultas)
                                    <div class="mt-1 text-sm font-medium text-[#4E566E]">{{ $programFakultas }}</div>
                                @endif
                            </div>
                            @if($programKuota)
                                <div class="shrink-0 text-right">
                                    <div class="text-sm font-semibold text-[#4E566E]">Kuota</div>
                                    <div class="mt-2 font-numeric text-xl font-bold leading-snug tabular-nums text-[#3E465C]">
                                        {{ $programKuota }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 h-[3px] w-14 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" aria-hidden="true"></div>

                        @if($programDeskripsi)
                            <p class="mt-5 text-base leading-relaxed text-[#3E465C]">
                                {{ $programDeskripsi }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-5 text-[15px] font-semibold text-[#4E566E] transition group-hover:text-[#14141F]">
                        Lihat detail program studi <span aria-hidden="true">&rarr;</span>
                    </div>
                </a>
            @empty
                <div class="glass-card-jalur rounded-2xl p-7 text-base text-[#3E465C] md:col-span-2 lg:col-span-3">
                    Belum ada data program studi yang tersedia.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
