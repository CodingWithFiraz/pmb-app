@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<div class="text-center py-16">
    <h1 class="text-4xl font-bold text-blue-700 mb-4">Penerimaan Mahasiswa Baru</h1>
    <p class="text-gray-600 text-lg mb-8">Daftarkan dirimu dan mulai perjalanan akademikmu bersama kami.</p>

    @if($activePeriod)
        <div class="bg-green-50 border border-green-300 rounded-xl p-6 max-w-lg mx-auto mb-8">
            <div class="text-green-700 font-semibold text-lg">📢 Pendaftaran Sedang Dibuka</div>
            <div class="text-gray-700 mt-1">{{ $activePeriod->name }}</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ $activePeriod->starts_at->format('d M Y') }} — {{ $activePeriod->ends_at->format('d M Y') }}
            </div>
            @if($activePeriod->registration_fee > 0)
                <div class="text-sm text-gray-600 mt-1">Biaya: Rp {{ number_format($activePeriod->registration_fee, 0, ',', '.') }}</div>
            @else
                <div class="text-sm text-green-600 mt-1 font-medium">✓ Pendaftaran Gratis</div>
            @endif
        </div>
    @else
        <div class="bg-gray-100 border rounded-xl p-6 max-w-lg mx-auto mb-8 text-gray-600">
            Saat ini tidak ada periode pendaftaran yang aktif. Pantau terus informasi kami.
        </div>
    @endif

    <div class="flex gap-4 justify-center">
        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">Daftar Sekarang</a>
        <a href="{{ route('public.info') }}" class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50">Lihat Informasi</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl mb-3">📝</div>
        <h3 class="font-bold text-lg mb-2">Isi Formulir</h3>
        <p class="text-gray-500 text-sm">Lengkapi data diri dan pendidikan kamu secara online.</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl mb-3">📎</div>
        <h3 class="font-bold text-lg mb-2">Unggah Dokumen</h3>
        <p class="text-gray-500 text-sm">Upload dokumen persyaratan dalam format PDF atau gambar.</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-3xl mb-3">🔔</div>
        <h3 class="font-bold text-lg mb-2">Pantau Status</h3>
        <p class="text-gray-500 text-sm">Cek perkembangan pendaftaran kamu kapan saja.</p>
    </div>
</div>
@endsection