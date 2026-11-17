@extends('layouts.app')
@section('title', 'Program Studi')

@section('content')
{{-- Section Header --}}
<div class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-black font-heading tracking-tight">
        Program Studi
    </h1>
    <a href="{{ route('public.fakultas') }}" class="text-xs font-semibold bg-amber-100 text-amber-900 px-3 py-1.5 rounded-full hover:bg-amber-200 transition">
        Lihat Berdasarkan Fakultas (TODO UI) &rarr;
    </a>
</div>

{{-- Programs Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
    @forelse($programs as $program)
        @php
            $programNama = $program->nama ?? $program->name ?? 'Program Studi';
            $programFakultas = $program->fakultas?->nama ?? $program->faculty ?? 'Program Unggulan';
            $programKuota = $program->kuota ?? $program->quota ?? null;
            $programDeskripsi = $program->deskripsi ?? $program->description ?? null;

            $pName = strtolower($programNama . ' ' . $programFakultas);
            $isTech = str_contains($pName, 'informatika') || str_contains($pName, 'sistem') || str_contains($pName, 'komputer') || str_contains($pName, 'software') || str_contains($pName, 'multimedia');
            $isBusiness = str_contains($pName, 'manajemen') || str_contains($pName, 'akuntansi') || str_contains($pName, 'bisnis') || str_contains($pName, 'ekonomi');
            $isLaw = str_contains($pName, 'hukum');
            $isCreative = str_contains($pName, 'desain') || str_contains($pName, 'grafika') || str_contains($pName, 'animasi') || str_contains($pName, 'penerbitan') || str_contains($pName, 'mode');
        @endphp

        <article class="group relative rounded-2xl bg-white border border-[#DBDCFA] p-6 sm:p-7 shadow-xs hover:border-[#5F4AF7]/50 hover:shadow-md transition-all duration-200 flex flex-col justify-between gap-6">
            {{-- Top Row: Large Decorative Icon & Quota --}}
            <div class="flex items-start justify-between gap-4">
                <div class="text-[#818CA2] group-hover:scale-105 transition-transform duration-200 shrink-0">
                    @if($isTech)
                        <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                        </svg>
                    @elseif($isBusiness)
                        <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                    @elseif($isLaw)
                        <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                        </svg>
                    @elseif($isCreative)
                        <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42" />
                        </svg>
                    @else
                        <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    @endif
                </div>

                @if($programKuota)
                    <div class="text-right">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-[#818CA2] block">Kuota</span>
                        <span class="text-lg sm:text-xl font-extrabold text-[#5F4AF7] font-numeric leading-none mt-0.5 block">
                            {{ $programKuota }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Bottom Section: Metadata Sistem Baru + Nama Prodi --}}
            <div class="flex flex-col gap-1">
                @if(!empty($program->kode) || !empty($program->jenjang))
                    <div class="flex items-center gap-2 mb-1">
                        @if(!empty($program->kode))
                            <span class="text-[11px] font-mono font-bold text-[#5F4AF7] bg-[#F2F5FF] px-2 py-0.5 rounded">
                                {{ $program->kode }}
                            </span>
                        @endif
                        @if(!empty($program->jenjang))
                            <span class="text-[11px] font-semibold text-[#818CA2]">
                                {{ $program->jenjang }}
                            </span>
                        @endif
                    </div>
                @endif

                <h3 class="text-xl sm:text-2xl font-extrabold text-black font-heading tracking-tight group-hover:text-[#5F4AF7] transition duration-200 leading-snug">
                    <a href="{{ route('public.prodi', $program) }}" class="hover:underline">
                        {{ $programNama }}
                    </a>
                </h3>
                <div class="text-xs sm:text-sm font-semibold text-[#818CA2] mt-0.5">
                    {{ $programFakultas }}
                </div>
                @if($programDeskripsi)
                    <p class="text-xs text-[#818CA2] mt-2.5 leading-relaxed line-clamp-2">
                        {{ $programDeskripsi }}
                    </p>
                @endif

                <div class="mt-3 pt-3 border-t border-[#E5EAFC]">
                    <a href="{{ route('public.prodi', $program) }}" class="text-xs font-semibold text-[#5F4AF7] hover:underline">
                        Lihat detail program studi &rarr;
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div class="bg-white rounded-3xl p-12 border border-[#DBDCFA] text-center text-[#818CA2] shadow-xs col-span-full max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-[#F2F5FF] text-[#5F4AF7] border border-[#DBDCFA] flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <p class="font-bold text-sm text-black mb-1">Belum Ada Program Studi</p>
            <p class="text-xs text-[#818CA2]">Informasi program studi dan kuota akan segera diperbarui.</p>
        </div>
    @endforelse
</div>
@endsection