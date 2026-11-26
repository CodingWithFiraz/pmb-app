@extends('layouts.app')
@section('title', $prodi->nama)
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-10 pb-14 lg:pt-12 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="mb-8 flex flex-wrap items-center gap-1.5 text-sm text-[#4E566E]">
            <a href="{{ route('home') }}" class="transition hover:text-[#14141F]">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('public.fakultas') }}" class="transition hover:text-[#14141F]">Fakultas</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('public.fakultas.detail', $prodi->fakultas) }}" class="transition hover:text-[#14141F]">{{ $prodi->fakultas->nama }}</a>
            <span aria-hidden="true">/</span>
            <span class="font-medium text-[#14141F]">{{ $prodi->nama }}</span>
        </nav>

        {{-- Header program studi: teks tanpa container --}}
        <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <div class="hero-enter text-sm font-semibold uppercase tracking-wider text-[#4E566E]">
                    {{ $prodi->kode }} &middot; {{ $prodi->jenjang }} &middot; {{ $prodi->fakultas->nama }}
                </div>
                <h1 class="hero-enter mt-2 font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                    {{ $prodi->nama }}
                </h1>
                @if($prodi->deskripsi)
                    <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                        {{ $prodi->deskripsi }}
                    </p>
                @endif
            </div>

            <div class="hero-enter-delay-1 flex shrink-0 gap-8 sm:text-right">
                @if($prodi->kuota)
                    <div>
                        <div class="text-sm font-semibold text-[#4E566E]">Kuota</div>
                        <div class="mt-1 font-numeric text-3xl font-bold tabular-nums text-[#14141F]">
                            {{ $prodi->kuota }}
                        </div>
                    </div>
                @endif
                <div>
                    <div class="text-sm font-semibold text-[#4E566E]">Jenjang</div>
                    <div class="mt-1 font-heading text-3xl font-bold text-[#14141F]">
                        {{ $prodi->jenjang }}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-12">
            {{-- Tarif UKT --}}
            @if($tarif->isNotEmpty())
                <article class="glass-card-jalur rounded-2xl p-7 sm:p-8">
                    <h2 class="font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
                        Biaya UKT
                    </h2>
                    <p class="mt-3 mb-6 text-base leading-relaxed text-[#3E465C]">
                        {{ $periode->nama }} &mdash; besaran UKT untuk {{ $prodi->nama }} pada setiap jalur masuk dan golongan UKT.
                    </p>

                    <div class="overflow-hidden rounded-2xl border border-[#E6E1EC] bg-white">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="border-b border-[#EFEBF4] bg-[#FAF8FC] text-xs font-semibold text-[#4E566E]">
                                    <tr>
                                        <th class="h-12 pl-5 pr-3">Jalur</th>
                                        @foreach ($golongan as $g)
                                            <th class="h-12 px-3 text-right last:pr-5">{{ $g->kode }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#EFEBF4]">
                                    @foreach($tarif as $namaJalur => $rows)
                                        <tr class="transition-colors hover:bg-[#FAF8FC]">
                                            <td class="py-3.5 pl-5 pr-3 align-middle font-semibold text-[#14141F]">{{ $namaJalur }}</td>
                                            @foreach ($golongan as $g)
                                                @php $row = $rows->firstWhere('golongan.kode', $g->kode); @endphp
                                                <td class="px-3 py-3.5 text-right align-middle whitespace-nowrap last:pr-5 {{ $row ? 'font-numeric font-medium text-[#14141F] tabular-nums' : 'text-[#4E566E]' }}">
                                                    {{ $row ? 'Rp '.number_format($row->nominal, 0, ',', '.') : '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <p class="mt-5 text-[13px] font-medium text-[#4E566E]">
                        Nominal dalam Rupiah (Rp) per tahun akademik.
                    </p>
                </article>
            @endif

            {{-- Prodi lain di fakultas yang sama --}}
            @if($prodiLain->isNotEmpty())
                <div>
                    <h2 class="mb-6 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
                        Program Studi Lain di {{ $prodi->fakultas->nama }}
                    </h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($prodiLain as $p)
                            <a href="{{ route('public.prodi', $p) }}"
                               class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                                {{ $p->kode }} &middot; {{ $p->jenjang }}
                                            </div>
                                            <h3 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E]">
                                                {{ $p->nama }}
                                            </h3>
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
            @endif
        </div>
    </div>
</section>
@endsection
