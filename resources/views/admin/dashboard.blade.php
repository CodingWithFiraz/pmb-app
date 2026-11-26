@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6">
    {{-- Header ringkas halaman (tanpa hero) --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">
                Dashboard Admin PMB
            </h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">
                Ringkasan pendaftar, status verifikasi berkas, dan rekap pembayaran periode aktif.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.pendaftaran.export') }}"
               class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-[#E6E1EC] bg-white px-4 text-sm font-semibold text-[#14141F] transition duration-150 hover:-translate-y-0.5 hover:border-[#BF8AFC] hover:bg-[#FAF8FC] active:translate-y-0">
                <svg class="size-4 text-[#4E566E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.pendaftaran.index') }}"
               class="inline-flex h-10 items-center justify-center rounded-xl bg-black px-4 text-sm font-semibold text-white transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
                Daftar Pendaftar
            </a>
        </div>
    </div>

    @if($activePeriod)
        <div class="bg-[#ECFDF5] border border-[#007A55]/20 rounded-xl p-4 mb-6 text-[#007A55]">
            Periode Aktif: <strong>{{ $activePeriod->nama }}</strong>
            ({{ $activePeriod->tanggal_mulai->translatedFormat('d M Y') }} &mdash; {{ $activePeriod->tanggal_selesai->translatedFormat('d M Y') }})
        </div>
    @endif

    {{-- Statistik utama (gaya kartu pastel dalam kontainer putih ber-stroke) --}}
    <div class="mb-8 rounded-2xl border border-[#E6E1EC] bg-white p-4 shadow-xs sm:p-5">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach([
                [
                    'label'  => 'Total Pendaftar',
                    'value'  => $stats['total'],
                    'card'   => 'bg-[#F2F5FF] border-[#606ADF]/20',
                    'number' => 'text-[#606ADF]',
                ],
                [
                    'label'  => 'Menunggu Verifikasi',
                    'value'  => $stats['menunggu_verifikasi'],
                    'card'   => 'bg-[#FFFBEB] border-[#BB4D00]/20',
                    'number' => 'text-[#BB4D00]',
                ],
                [
                    'label'  => 'Terverifikasi',
                    'value'  => $stats['terverifikasi'],
                    'card'   => 'bg-[#ECFDF5] border-[#007A55]/20',
                    'number' => 'text-[#007A55]',
                ],
                [
                    'label'  => 'Sudah Bayar',
                    'value'  => $stats['sudah_bayar'],
                    'card'   => 'bg-[#F2F5FF] border-[#606ADF]/20',
                    'number' => 'text-[#606ADF]',
                ],
                [
                    'label'  => 'Belum Bayar',
                    'value'  => $stats['belum_bayar'],
                    'card'   => 'bg-[#FEF2F2] border-[#C10007]/20',
                    'number' => 'text-[#C10007]',
                ],
                [
                    'label'  => 'Tagihan Menunggu',
                    'value'  => $stats['menunggu_bayar'],
                    'card'   => 'bg-[#FFFBEB] border-[#BB4D00]/20',
                    'number' => 'text-[#BB4D00]',
                ],
            ] as $stat)
                <div class="flex flex-col justify-between rounded-xl border p-4 text-left {{ $stat['card'] }}">
                    <div class="text-xs font-semibold leading-snug text-black">
                        {{ $stat['label'] }}
                    </div>
                    <div class="mt-2 font-numeric text-3xl font-extrabold leading-none tracking-tight tabular-nums {{ $stat['number'] }}" data-stat="{{ $stat['label'] }}">
                        {{ $stat['value'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Dua kartu analitik & ringkasan (satu sistem kartu putih ber-stroke) --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Pendaftar per jalur (gaya Resource Usage — Medesk Dashboard Watermelon UI) --}}
        <section class="flex flex-col gap-6 rounded-2xl border border-[#E6E1EC] bg-white p-6">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-heading text-lg font-bold tracking-tight text-[#14141F]">Pendaftar per Jalur</h2>
                <a href="{{ route('admin.jalur-masuk.index') }}"
                   class="inline-flex shrink-0 items-center gap-1 rounded-md text-sm font-medium text-[#4E566E] transition hover:text-[#14141F] hover:underline">
                    <span>Kelola</span>
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </a>
            </div>

            @if($jalurList->isEmpty())
                <p class="text-sm text-[#4E566E]">Belum ada jalur masuk.</p>
            @else
                <ul class="flex flex-col gap-5">
                    @foreach($jalurList as $idx => $j)
                        @php
                            $kode   = strtoupper($j['kode']);
                            $persen = $stats['total'] > 0 ? round($j['total'] / $stats['total'] * 100) : 0;
                            $theme  = match($kode) {
                                'SNBP'    => ['tile' => 'bg-[#F5EFFB] text-[#7E22CE]', 'bar' => 'bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]'],
                                'SNBT'    => ['tile' => 'bg-[#F5EFFB] text-[#7E22CE]', 'bar' => 'bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]'],
                                'MANDIRI' => ['tile' => 'bg-[#F5EFFB] text-[#7E22CE]', 'bar' => 'bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]'],
                                default   => ['tile' => 'bg-[#F5EFFB] text-[#7E22CE]', 'bar' => 'bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]'],
                            };
                        @endphp
                        <li data-jalur="{{ $j['kode'] }}" class="flex gap-4 {{ $idx > 0 ? 'border-t border-[#EFEBF4] pt-5' : '' }}">
                            <div class="flex size-9 shrink-0 items-center justify-center rounded-lg {{ $theme['tile'] }}" aria-hidden="true">
                                @if($kode === 'SNBP')
                                    {{-- Prestasi & Rapor (Award/Medali) --}}
                                    <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                    </svg>
                                @elseif($kode === 'SNBT')
                                    {{-- Tes Berbasis Komputer (Monitor UTBK) --}}
                                    <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                    </svg>
                                @elseif($kode === 'MANDIRI')
                                    {{-- Seleksi Mandiri (Dokumen Ujian) --}}
                                    <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                @else
                                    {{-- Jalur Lainnya (Topi Wisuda) --}}
                                    <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                    </svg>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3 text-sm">
                                    <span class="truncate font-semibold text-[#14141F]" title="{{ $j['nama'] }}">{{ $j['nama'] }}</span>
                                    <span class="shrink-0 text-xs text-[#4E566E] tabular-nums">
                                        <span class="font-numeric text-sm font-bold text-[#14141F]" data-jalur-total="{{ $j['kode'] }}">{{ $j['total'] }}</span>
                                        <span class="ml-0.5">({{ $persen }}%)</span>
                                    </span>
                                </div>
                                <div class="mt-2 h-2 overflow-hidden rounded-full bg-[#F8F6F9]">
                                    <span class="block h-full rounded-full transition-all duration-300 {{ $theme['bar'] }}" style="width: {{ $persen }}%"></span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Ringkasan pembayaran --}}
        <section class="flex flex-col gap-6 rounded-2xl border border-[#E6E1EC] bg-white p-6">
            <div class="flex items-center justify-between gap-2">
                <h2 class="font-heading text-lg font-bold tracking-tight text-[#14141F]">Ringkasan Pembayaran</h2>
                <a href="{{ route('admin.pembayaran.verifikasi.index') }}"
                   class="inline-flex shrink-0 items-center gap-1 rounded-md text-sm font-medium text-[#4E566E] transition hover:text-[#14141F] hover:underline">
                    <span>Verifikasi</span>
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </a>
            </div>

            <ul class="divide-y divide-[#EFEBF4] text-sm">
                <li class="flex items-center justify-between gap-3 py-3.5 first:pt-0">
                    <span class="inline-flex items-center gap-3 font-medium text-[#14141F]">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg border border-[#606ADF]/20 bg-[#F2F5FF] text-[#606ADF]" aria-hidden="true">
                            <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                        </span>
                        Total tagihan
                    </span>
                    <span class="font-numeric text-base font-bold text-[#606ADF] tabular-nums">{{ $ringkasanTagihan['total'] }}</span>
                </li>
                <li class="flex items-center justify-between gap-3 py-3.5">
                    <span class="inline-flex items-center gap-3 font-medium text-[#14141F]">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#ECFDF5] text-[#007A55]" aria-hidden="true">
                            <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        Lunas
                    </span>
                    <span class="font-numeric text-base font-bold text-[#007A55] tabular-nums">{{ $ringkasanTagihan['paid'] }}</span>
                </li>
                <li class="flex items-center justify-between gap-3 py-3.5">
                    <span class="inline-flex items-center gap-3 font-medium text-[#14141F]">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#FFFBEB] text-[#BB4D00]" aria-hidden="true">
                            <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        Menunggu pembayaran
                    </span>
                    <span class="font-numeric text-base font-bold text-[#BB4D00] tabular-nums">{{ $ringkasanTagihan['pending'] }}</span>
                </li>
                <li class="flex items-center justify-between gap-3 py-3.5 last:pb-0">
                    <span class="inline-flex items-center gap-3 font-medium text-[#14141F]">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#FEF2F2] text-[#C10007]" aria-hidden="true">
                            <svg class="size-[18px]" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </span>
                        Kedaluwarsa / gagal
                    </span>
                    <span class="font-numeric text-base font-bold text-[#C10007] tabular-nums">{{ $ringkasanTagihan['gagal'] }}</span>
                </li>
            </ul>
        </section>
    </div>

    {{-- Tabel pendaftar terbaru --}}
    <section class="overflow-hidden rounded-2xl border border-[#E6E1EC] bg-white">
        <div class="flex flex-col gap-3 border-b border-[#EFEBF4] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-heading text-lg font-bold tracking-tight text-[#14141F]">10 Pendaftar Terbaru</h2>
                <span class="rounded-lg bg-[#F8F6F9] px-2.5 py-0.5 text-xs font-semibold text-[#7E22CE] tabular-nums">
                    {{ $recentPendaftar->count() }} data
                </span>
            </div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="text-sm font-semibold text-[#7E22CE] hover:text-[#581C87] hover:underline">
                Lihat semua pendaftar &rarr;
            </a>
        </div>

        @if($recentPendaftar->isEmpty())
            <div class="px-6 py-10 text-center text-sm text-[#4E566E]">
                Belum ada pendaftar pada periode ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-[#EFEBF4] bg-[#FAF8FC] text-xs font-semibold uppercase tracking-wide text-[#4E566E]">
                        <tr>
                            <th class="px-6 py-3.5">Pendaftar</th>
                            <th class="px-4 py-3.5">Jalur</th>
                            <th class="px-4 py-3.5">Program Studi</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFEBF4]">
                        @foreach($recentPendaftar as $p)
                            @php
                                $initials = collect(explode(' ', trim($p->user->name ?? 'P')))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                                    ->implode('');
                            @endphp
                            <tr class="transition hover:bg-[#FAF8FC]">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#F8F6F9] font-heading text-xs font-bold text-[#7E22CE]" aria-hidden="true">
                                            {{ $initials ?: 'P' }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-[#14141F]">{{ $p->user->name }}</div>
                                            <div class="font-mono text-xs text-[#4E566E]">{{ $p->nomor_pendaftaran ?? 'Belum ada no. daftar' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-[#F8F6F9] px-2.5 py-1 text-xs font-bold text-[#7E22CE]">
                                        {{ $p->jalur?->kode ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 font-medium text-[#14141F]">
                                    {{ $p->prodi?->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                                        @if($p->status === 'terverifikasi')
                                            bg-emerald-50 text-emerald-700
                                        @elseif($p->status === 'ditolak')
                                            bg-rose-50 text-rose-700
                                        @elseif($p->status === 'menunggu_verifikasi')
                                            bg-amber-50 text-amber-800
                                        @else
                                            bg-[#F8F6F9] text-[#4E566E]
                                        @endif">
                                        <span class="h-1.5 w-1.5 rounded-full
                                            @if($p->status === 'terverifikasi')
                                                bg-emerald-500
                                            @elseif($p->status === 'ditolak')
                                                bg-rose-500
                                            @elseif($p->status === 'menunggu_verifikasi')
                                                bg-amber-500
                                            @else
                                                bg-slate-400
                                            @endif"></span>
                                        {{ $p->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.pendaftaran.show', $p) }}"
                                       class="inline-flex items-center gap-1 rounded-lg border border-[#E6E1EC] bg-white px-3 py-1.5 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:text-[#7E22CE]">
                                        Detail <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
