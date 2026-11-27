@extends('layouts.pendaftar')
@section('title', 'Kartu Peserta')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Header kartu --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-6 border border-[#E6E1EC]">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="text-xs font-mono text-[#4E566E] mb-1">POLITEKNIK NEGERI MEDIA BANTEN</div>
                <h1 class="text-2xl font-bold text-[#14141F]">Kartu Peserta PMB</h1>
            </div>
            <div class="text-right hidden sm:block">
                <div class="text-xs text-[#4E566E]">PMB {{ $pendaftaran->periode->tahun_akademik }}</div>
                <div class="text-sm font-bold text-[#14141F]">{{ $pendaftaran->jalur?->kode }}</div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- Foto --}}
            <div class="relative">
                @if($pendaftaran->foto_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($pendaftaran->foto_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('local')->url($pendaftaran->foto_path) }}"
                        alt="Foto" class="w-24 h-24 rounded-full object-cover border-4 border-[#EFEBF4]">
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
                <div class="text-xs text-[#4E566E] uppercase tracking-wider">Nama Peserta</div>
                <div class="text-lg font-bold text-[#14141F]">{{ auth()->user()->name }}</div>
                <div class="mt-2 text-xs text-[#4E566E] uppercase tracking-wider">Nomor Pendaftaran</div>
                <div class="text-xl font-mono font-bold text-[#7E22CE]">{{ $pendaftaran->nomor_pendaftaran }}</div>
                <div class="mt-2 text-xs text-[#4E566E] uppercase tracking-wider">Program Studi</div>
                <div class="text-base font-semibold text-[#14141F]">{{ $pendaftaran->prodi?->nama }} ({{ $pendaftaran->prodi?->kode }})</div>
                <div class="mt-1 text-xs text-[#4E566E]">{{ $pendaftaran->prodi->fakultas->nama }}</div>
            </div>
        </div>

        {{-- Barcode --}}
        <div class="mt-6 pt-4 border-t border-[#E6E1EC]">
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
            <div class="mt-2 text-center text-xs font-mono text-[#4E566E]">
                {{ $pendaftaran->nomor_pendaftaran }}
            </div>
        </div>
    </div>

    {{-- Info tambahan --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <h3 class="font-bold text-lg mb-4">Informasi Pendaftaran</h3>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <dt class="text-[#4E566E]">Jalur Masuk</dt>
            <dd class="font-medium text-[#14141F]">{{ $pendaftaran->jalur?->nama }}</dd>

            <dt class="text-[#4E566E]">Golongan UKT</dt>
            <dd class="font-medium text-[#14141F]">{{ $pendaftaran->golongan?->nama }}</dd>

            <dt class="text-[#4E566E]">Tahun Akademik</dt>
            <dd class="font-medium text-[#14141F]">{{ $pendaftaran->periode->tahun_akademik }}</dd>

            <dt class="text-[#4E566E]">Status Pembayaran</dt>
            <dd class="font-medium text-green-700">
                @php $allPaid = $pendaftaran->pembayaran->every(fn ($t) => $t->status === 'paid'); @endphp
                {{ $allPaid ? 'Lunas' : 'Belum Lunas' }}
            </dd>
        </dl>
    </div>

    {{-- Tombol cetak --}}
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <button onclick="window.print()"
            class="rounded-lg border border-[#E6E1EC] px-6 py-2 text-sm font-medium text-[#14141F] hover:bg-[#FAF8FC] flex-1 sm:flex-1">
            Cetak Kartu
        </button>
        <a href="{{ route('pendaftar.pengumuman') }}"
            class="rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 py-2 text-sm font-semibold text-white text-center hover:opacity-95 flex-1 sm:flex-1">
            Kembali ke Pengumuman
        </a>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .no-print, nav, footer, .bg-[#FAF8FC] { display: none !important; }
        .print-only { display: block !important; }
        .max-w-lg { max-width: none !important; width: 100% !important; }
        .shadow { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>
@endsection
