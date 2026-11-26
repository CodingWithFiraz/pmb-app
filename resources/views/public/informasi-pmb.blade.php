@extends('layouts.app')
@section('title', 'Informasi PMB')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Header halaman: teks tanpa container --}}
        <div class="mb-10 max-w-2xl">
            <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                Informasi PMB
            </h1>
            <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Jalur masuk, biaya pendaftaran, dan tarif Uang Kuliah Tunggal (UKT).
            </p>
        </div>

        @if(! $periode)
            <div class="glass-card-jalur rounded-2xl p-7 text-base text-[#3E465C]">
                Saat ini belum ada periode pendaftaran yang aktif. Silakan cek halaman berita untuk informasi terbaru.
            </div>
        @else
            <div class="space-y-10">
                {{-- Jadwal --}}
                <article class="glass-card-jalur rounded-2xl p-7 sm:p-8">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                Jadwal Pendaftaran
                            </div>
                            <h2 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-2xl">
                                {{ $periode->nama }}
                            </h2>
                            <div class="mt-1 text-base text-[#3E465C]">Tahun Akademik {{ $periode->tahun_akademik }}</div>
                        </div>
                        <dl class="grid grid-cols-2 gap-6 sm:text-right">
                            <div>
                                <dt class="text-sm font-semibold text-[#4E566E]">Dibuka</dt>
                                <dd class="mt-2 whitespace-nowrap font-numeric text-lg font-bold tabular-nums text-[#14141F]">
                                    {{ $periode->tanggal_mulai->translatedFormat('d F Y') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-[#4E566E]">Ditutup</dt>
                                <dd class="mt-2 whitespace-nowrap font-numeric text-lg font-bold tabular-nums text-[#14141F]">
                                    {{ $periode->tanggal_selesai->translatedFormat('d F Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </article>

                {{-- Jalur masuk & biaya pendaftaran --}}
                <article class="glass-card-jalur rounded-2xl p-7 sm:p-8">
                    <h2 class="mb-6 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
                        Jalur Masuk
                    </h2>

                    {{-- Desain tabel mengikuti tabel admin/periode --}}
                    <div class="overflow-hidden rounded-2xl border border-[#DDE1F5] bg-white">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="border-b border-[#E7EAF8] bg-[#F8F9FF] text-xs font-semibold text-[#4E566E]">
                                    <tr>
                                        <th class="h-12 pl-5 pr-3">Kode</th>
                                        <th class="h-12 px-3">Jalur</th>
                                        <th class="h-12 px-3">Deskripsi</th>
                                        <th class="h-12 pl-3 pr-5 text-right">Biaya Pendaftaran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#EEF0FA]">
                                    @foreach($jalur as $j)
                                        @php $b = $biaya->get($j->nama); @endphp
                                        <tr class="transition-colors hover:bg-[#F8F9FF]">
                                            <td class="py-3.5 pl-5 pr-3 align-middle">
                                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-[#F5EFFB] text-[#7E22CE]">{{ $j->kode }}</span>
                                            </td>
                                            <td class="px-3 py-3.5 align-middle font-semibold text-[#14141F]">{{ $j->nama }}</td>
                                            <td class="px-3 py-3.5 align-middle text-[#4E566E]">{{ $j->deskripsi }}</td>
                                            <td class="py-3.5 pl-3 pr-5 text-right align-middle whitespace-nowrap">
                                                @if($b && $b->nominal !== null)
                                                    <span class="font-numeric font-medium text-[#14141F] tabular-nums">Rp {{ number_format($b->nominal, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-emerald-600/10 text-emerald-700">Gratis</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>

                {{-- Tarif UKT --}}
                <article class="glass-card-jalur rounded-2xl p-7 sm:p-8">
                    <h2 class="font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
                        Biaya UKT
                    </h2>
                    <p class="mt-3 mb-6 text-base leading-relaxed text-[#3E465C]">
                        Besaran UKT per tahun akademik untuk {{ $periode->nama }}, dikelompokkan per jalur masuk, program studi, dan golongan UKT.
                    </p>

                    <div class="space-y-8">
                        @foreach($tarif as $namaJalur => $data)
                            @if($data['prodi']->isNotEmpty())
                                <div>
                                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-[#4E566E]">{{ $namaJalur }}</h3>

                                    <div class="overflow-hidden rounded-2xl border border-[#DDE1F5] bg-white">
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-sm">
                                                <thead class="border-b border-[#E7EAF8] bg-[#F8F9FF] text-xs font-semibold text-[#4E566E]">
                                                    <tr>
                                                        <th class="h-12 pl-5 pr-3">Program Studi</th>
                                                        @foreach($golongan as $g)
                                                            <th class="h-12 px-3 text-right last:pr-5">{{ $g->kode }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-[#EEF0FA]">
                                                    @foreach($data['prodi'] as $p)
                                                        <tr class="transition-colors hover:bg-[#F8F9FF]">
                                                            <td class="py-3.5 pl-5 pr-3 align-middle font-semibold text-[#14141F]">{{ $p->nama }}</td>
                                                            @foreach($golongan as $g)
                                                                @php $row = $data['tarif'][$p->id][$g->id] ?? null; @endphp
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
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <p class="mt-5 text-[13px] font-medium text-[#4E566E]">
                        Seluruh nominal dalam Rupiah (Rp) per tahun akademik.
                    </p>
                </article>

                {{-- Program studi per fakultas --}}
                <div>
                    <h2 class="mb-6 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F] sm:text-3xl">
                        Program Studi
                    </h2>

                    <div class="space-y-10">
                        @foreach($prodiPerFakultas as $namaFakultas => $list)
                            <div>
                                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#4E566E]">{{ $namaFakultas }}</h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    @foreach($list as $p)
                                        <a href="{{ route('public.prodi', $p) }}"
                                           class="glass-card-jalur group flex h-full flex-col justify-between rounded-2xl p-7 transition duration-200 hover:-translate-y-0.5">
                                            <div>
                                                <div class="text-sm font-semibold uppercase tracking-wide text-[#4E566E]">
                                                    {{ $p->kode }}@if($p->jenjang) &middot; {{ $p->jenjang }}@endif
                                                </div>
                                                <h4 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E]">
                                                    {{ $p->nama }}
                                                </h4>
                                                <div class="mt-5 h-[3px] w-14 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" aria-hidden="true"></div>
                                            </div>
                                            <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">
                                                Lihat detail prodi <span aria-hidden="true">&rarr;</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
