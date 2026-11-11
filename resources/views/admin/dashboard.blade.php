@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    @if($activePeriod)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-blue-800">
            Periode Aktif: <strong>{{ $activePeriod->nama }}</strong>
            ({{ $activePeriod->tanggal_mulai->translatedFormat('d M Y') }} &mdash; {{ $activePeriod->tanggal_selesai->translatedFormat('d M Y') }})
        </div>
    @endif

    {{-- Statistik utama --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Pendaftar', 'value' => $stats['total'],               'color' => 'bg-gray-100 text-gray-800'],
            ['label' => 'Menunggu Verifikasi', 'value' => $stats['menunggu_verifikasi'], 'color' => 'bg-amber-100 text-amber-700'],
            ['label' => 'Terverifikasi',    'value' => $stats['terverifikasi'],       'color' => 'bg-green-100 text-green-700'],
            ['label' => 'Sudah Bayar',      'value' => $stats['sudah_bayar'],         'color' => 'bg-blue-100 text-blue-700'],
            ['label' => 'Belum Bayar',      'value' => $stats['belum_bayar'],         'color' => 'bg-red-100 text-red-700'],
            ['label' => 'Tagihan Menunggu', 'value' => $stats['menunggu_bayar'],      'color' => 'bg-gray-100 text-gray-700'],
        ] as $stat)
            <div class="bg-white shadow rounded-xl p-4 text-center">
                <div class="text-2xl font-bold {{ $stat['color'] }} rounded-lg py-1" data-stat="{{ $stat['label'] }}">{{ $stat['value'] }}</div>
                <div class="text-xs text-gray-600 mt-1">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        {{-- Pendaftar per jalur --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="font-bold text-lg mb-4">Pendaftar per Jalur</h2>

            @if($jalurList->isEmpty())
                <p class="text-gray-500 text-sm">Belum ada jalur masuk.</p>
            @else
                <ul class="space-y-3">
                    @foreach($jalurList as $j)
                        <li data-jalur="{{ $j['kode'] }}">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-700">{{ $j['nama'] }}</span>
                                <span class="font-semibold text-gray-800" data-jalur-total="{{ $j['kode'] }}">{{ $j['total'] }}</span>
                            </div>
                            <div class="h-2 w-full rounded bg-gray-100">
                                <div class="h-2 rounded bg-blue-600"
                                    style="width: {{ $stats['total'] > 0 ? round($j['total'] / $stats['total'] * 100) : 0 }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Status pendaftaran --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="font-bold text-lg mb-4">Status Pendaftaran</h2>

            <ul class="space-y-2 text-sm">
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Draft</span>
                    <span class="font-semibold text-gray-800">{{ $stats['draft'] }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Menunggu Verifikasi</span>
                    <span class="font-semibold text-gray-800">{{ $stats['menunggu_verifikasi'] }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Terverifikasi</span>
                    <span class="font-semibold text-gray-800">{{ $stats['terverifikasi'] }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Ditolak</span>
                    <span class="font-semibold text-gray-800">{{ $stats['ditolak'] }}</span>
                </li>
            </ul>
        </div>

        {{-- Ringkasan pembayaran --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="font-bold text-lg mb-4">Ringkasan Pembayaran</h2>

            <ul class="space-y-2 text-sm">
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Total tagihan</span>
                    <span class="font-semibold text-gray-800">{{ $ringkasanTagihan['total'] }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Lunas</span>
                    <span class="font-semibold text-green-700">{{ $ringkasanTagihan['paid'] }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Menunggu pembayaran</span>
                    <span class="font-semibold text-amber-700">{{ $ringkasanTagihan['pending'] }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-gray-600">Kedaluwarsa / gagal</span>
                    <span class="font-semibold text-red-700">{{ $ringkasanTagihan['gagal'] }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tabel pendaftar terbaru --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="font-bold text-lg mb-4">10 Pendaftar Terbaru</h2>

        @if($recentPendaftar->isEmpty())
            <p class="text-gray-500">Belum ada pendaftar.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-3 py-2">No. Daftar</th>
                            <th class="text-left px-3 py-2">Nama</th>
                            <th class="text-left px-3 py-2">Jalur</th>
                            <th class="text-left px-3 py-2">Program Studi</th>
                            <th class="text-left px-3 py-2">Status</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPendaftar as $p)
                            <tr class="border-t">
                                <td class="px-3 py-2 font-mono text-xs">{{ $p->nomor_pendaftaran ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $p->user->name }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $p->jalur?->kode ?? '-' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $p->prodi?->nama ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium
                                        @if($p->status === 'terverifikasi')
                                            bg-green-100 text-green-700
                                        @elseif($p->status === 'ditolak')
                                            bg-red-100 text-red-700
                                        @elseif($p->status === 'menunggu_verifikasi')
                                            bg-amber-100 text-amber-700
                                        @else
                                            bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $p->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.pendaftaran.show', $p) }}" class="text-blue-600 hover:underline text-xs">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
