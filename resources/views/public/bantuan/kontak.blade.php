@extends('layouts.public')
@section('title', 'Hubungi Kami')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Hubungi Kami</h1>
    <p class="text-gray-600 mt-1">Informasi kontak panitia PMB {{ $pengaturan['nama_singkat'] ?? 'PNM Jakarta' }}.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Detail kontak --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Kontak</h2>

        <dl class="space-y-4 text-sm">
            <div>
                <dt class="text-gray-500">Kampus</dt>
                <dd class="font-medium text-gray-800 mt-0.5">{{ $pengaturan['nama_kampus'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Alamat</dt>
                <dd class="text-gray-700 mt-0.5 leading-relaxed">{{ $pengaturan['alamat_kampus'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="mt-0.5">
                    <a href="mailto:{{ $pengaturan['email_kampus'] ?? '' }}" class="text-blue-600 hover:underline">
                        {{ $pengaturan['email_kampus'] ?? '-' }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Telepon</dt>
                <dd class="text-gray-700 mt-0.5">{{ $pengaturan['telepon_kampus'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">WhatsApp</dt>
                <dd class="text-gray-700 mt-0.5">{{ $pengaturan['wa_kampus'] ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jam Operasional</dt>
                <dd class="text-gray-700 mt-0.5">{{ $pengaturan['jam_operasional'] ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Peta --}}
    <div class="lg:col-span-2 bg-white shadow rounded-xl overflow-hidden">
        <h2 class="text-xl font-bold text-gray-800 p-6 pb-4">Lokasi Campus</h2>

        @if(! empty($pengaturan['maps_embed']))
            <div class="w-full h-96 border-t border-gray-100">
                <iframe
                    src="{{ $pengaturan['maps_embed'] }}"
                    class="w-full h-full"
                    style="border:0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta lokasi {{ $pengaturan['nama_kampus'] ?? 'kampus' }}"></iframe>
            </div>
        @else
            <div class="w-full h-96 border-t border-gray-100 flex items-center justify-center text-gray-500 bg-gray-50">
                Peta lokasi belum tersedia.
            </div>
        @endif
    </div>
</div>
@endsection
