@extends('layouts.pendaftar')
@section('title', 'Pengumuman Kelulusan')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pengumuman Kelulusan</h1>
                <p class="text-sm text-gray-600">
                    Periode {{ $pendaftaran?->periode->nama ?? '-' }}
                    @if($pendaftaran?->periode)
                        (TA {{ $pendaftaran->periode->tahun_akademik }})
                    @endif
                </p>
            </div>
            <a href="{{ route('pendaftar.dashboard') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Dashboard</a>
        </div>
    </div>

    {{-- Hasil belum dinyatakan --}}
    @if(!$pendaftaran || $pendaftaran->hasil_kelulusan === 'belum')
        <div class="bg-white shadow rounded-xl p-8 text-center">
            <div class="text-6xl mb-4">⏳</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Hasil Belum Dinyatakan</h2>
            <p class="text-gray-600 mb-6">
                Panitia PMB masih memproses berkas pendaftaran Anda. Hasil kelulusan akan diumumkan segera.
            </p>
            <a href="{{ route('pendaftar.dashboard') }}" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Kembali ke Dashboard
            </a>
        </div>
    @elseif($pendaftaran->hasil_kelulusan === 'tidak_lulus')
        <div class="bg-white shadow rounded-xl p-8 text-center">
            <div class="text-6xl mb-4">😔</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Maaf, Anda Tidak Lulus</h2>
            <p class="text-gray-600 mb-6">
                Hasil seleksi pendaftaran untuk
                <strong>{{ $pendaftaran->prodi?->nama }}</strong> pada jalur
                <strong>{{ $pendaftaran->jalur?->nama }}</strong> dinyatakan tidak lulus.
            </p>

            @if($pendaftaran->catatan_admin)
                <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 text-left">
                    <strong>Catatan Panitia:</strong> {{ $pendaftaran->catatan_admin }}
                </div>
            @endif

            <a href="{{ route('pendaftar.dashboard') }}" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Kembali ke Dashboard
            </a>
        </div>
    @elseif($pendaftaran->hasil_kelulusan === 'lulus')
        <div class="bg-white shadow rounded-xl p-8 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h2 class="text-2xl font-bold text-green-700 mb-2">Selamat, Anda Lulus!</h2>
            <p class="text-gray-600 mb-6">
                Anda telah lolos seleksi penerimaan mahasiswa baru untuk
                <strong>{{ $pendaftaran->prodi?->nama }}</strong> pada jalur
                <strong>{{ $pendaftaran->jalur?->nama }}</strong>.
            </p>

            <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 text-left">
                <strong>Nomor Pendaftaran:</strong> {{ $pendaftaran->nomor_pendaftaran }}<br>
                <strong>Program Studi:</strong> {{ $pendaftaran->prodi?->nama }} ({{ $pendaftaran->prodi?->kode }})<br>
                <strong>Jalur Masuk:</strong> {{ $pendaftaran->jalur?->nama }}
            </div>

            @if($pendaftaran->catatan_admin)
                <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 text-left">
                    <strong>Catatan Panitia:</strong> {{ $pendaftaran->catatan_admin }}
                </div>
            @endif

            {{-- Kartu peserta link --}}
            @if($tagihanLunas)
                <a href="{{ route('pendaftar.kartu') }}"
                    class="mt-6 inline-block rounded-lg bg-green-600 px-8 py-3 text-sm font-semibold text-white hover:bg-green-700 shadow-lg">
                    Unduh Kartu Peserta &rarr;
                </a>
            @else
                <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 text-left">
                    <strong>Kartu peserta belum tersedia:</strong> Silakan lunasi semua tagihan pembayaran terlebih dahulu.
                </div>
                <a href="{{ route('pendaftar.pembayaran') }}"
                    class="mt-4 inline-block rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Bayar Sekarang &rarr;
                </a>
            @endif

            <a href="{{ route('pendaftar.dashboard') }}" class="mt-4 inline-block text-sm text-gray-600 hover:underline">
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>
@endsection
