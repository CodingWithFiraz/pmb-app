@extends('layouts.pendaftar')
@section('title', 'Kartu Peserta')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Header kartu --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6 border border-gray-200">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="text-xs font-mono text-gray-500 mb-1">PNM JAKARTA</div>
                <h1 class="text-2xl font-bold text-gray-800">Kartu Peserta PMB</h1>
            </div>
            <div class="text-right hidden sm:block">
                <div class="text-xs text-gray-500">PMB {{ $pendaftaran->periode->tahun_akademik }}</div>
                <div class="text-sm font-bold text-gray-800">{{ $pendaftaran->jalur?->kode }}</div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- Foto --}}
            <div class="relative">
                @if($pendaftaran->foto_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($pendaftaran->foto_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('local')->url($pendaftaran->foto_path) }}"
                        alt="Foto" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold">
                        Foto
                    </div>
                @endif
                <span class="absolute -bottom-1 -right-1 bg-green-500 text-white text-xs rounded-full px-2 py-0.5">
                    {{ $pendaftaran->hasil_kelulusan === 'lulus' ? 'LULUS' : '' }}
                </span>
            </div>

            <div class="flex-1">
                <div class="text-xs text-gray-500 uppercase tracking-wider">Nama Peserta</div>
                <div class="text-lg font-bold text-gray-800">{{ auth()->user()->name }}</div>
                <div class="mt-2 text-xs text-gray-500 uppercase tracking-wider">Nomor Pendaftaran</div>
                <div class="text-xl font-mono font-bold text-blue-700">{{ $pendaftaran->nomor_pendaftaran }}</div>
                <div class="mt-2 text-xs text-gray-500 uppercase tracking-wider">Program Studi</div>
                <div class="text-base font-semibold text-gray-800">{{ $pendaftaran->prodi?->nama }} ({{ $pendaftaran->prodi?->kode }})</div>
                <div class="mt-1 text-xs text-gray-500">{{ $pendaftaran->prodi->fakultas->nama }}</div>
            </div>
        </div>

        {{-- Barcode --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex justify-center items-center space-x-1" id="barcode">
                @php
                    $kode = strtoupper($pendaftaran->nomor_pendaftaran ?? 'PMB-000000');
                    $binary = '';
                    foreach (str_split($kode) as $char) {
                        $binary .= str_pad(dechex(ord($char)), 2, '0', STR_PAD_LEFT);
                    }
                    $bits = str_split($binary);
                @endphp
                @foreach($bits as $index => $bit)
                    <div class="h-10 w-1 {{ ((int) $bit % 2 === 0) ? 'bg-black' : 'bg-white' }}"></div>
                @endforeach
            </div>
            <div class="mt-2 text-center text-xs font-mono text-gray-600">
                {{ $pendaftaran->nomor_pendaftaran }}
            </div>
        </div>
    </div>

    {{-- Info tambahan --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="font-bold text-lg mb-4">Informasi Pendaftaran</h3>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <dt class="text-gray-500">Jalur Masuk</dt>
            <dd class="font-medium text-gray-800">{{ $pendaftaran->jalur?->nama }}</dd>

            <dt class="text-gray-500">Golongan UKT</dt>
            <dd class="font-medium text-gray-800">{{ $pendaftaran->golongan?->nama }}</dd>

            <dt class="text-gray-500">Tahun Akademik</dt>
            <dd class="font-medium text-gray-800">{{ $pendaftaran->periode->tahun_akademik }}</dd>

            <dt class="text-gray-500">Status Pembayaran</dt>
            <dd class="font-medium text-green-700">
                @php $allPaid = $pendaftaran->pembayaran->every(fn ($t) => $t->status === 'paid'); @endphp
                {{ $allPaid ? 'Lunas' : 'Belum Lunas' }}
            </dd>
        </dl>
    </div>

    {{-- Tombol cetak --}}
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <button onclick="window.print()"
            class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 flex-1 sm:flex-1">
            Cetak Kartu
        </button>
        <a href="{{ route('pendaftar.pengumuman') }}"
            class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white text-center hover:bg-blue-700 flex-1 sm:flex-1">
            Kembali ke Pengumuman
        </a>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .no-print, nav, footer, .bg-gray-50 { display: none !important; }
        .print-only { display: block !important; }
        .max-w-lg { max-width: none !important; width: 100% !important; }
        .shadow { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>
@endsection
