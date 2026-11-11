@extends('layouts.public')
@section('title', 'Tentang Kampus')

@section('content')
{{-- Hero --}}
<div class="bg-gradient-to-r from-blue-700 to-blue-900 text-white rounded-2xl p-8 md:p-12 mb-8">
    <h1 class="text-3xl md:text-4xl font-bold mb-2">Tentang {{ $pengaturan['nama_singkat'] ?? 'Kampus' }}</h1>
    <p class="text-blue-100 max-w-3xl leading-relaxed">{{ $pengaturan['nama_kampus'] ?? '' }}</p>
</div>

{{-- Sejarah --}}
@if(! empty($pengaturan['sejarah']))
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Sejarah</h2>
        <p class="text-gray-600 leading-relaxed">{{ $pengaturan['sejarah'] }}</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Visi --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Visi</h2>
        <p class="text-gray-600 leading-relaxed">{{ $pengaturan['visi'] ?? '-' }}</p>
    </div>

    {{-- Misi --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Misi</h2>
        @if(! empty($pengaturan['misi']))
            <ul class="space-y-2 text-gray-600">
                @foreach (preg_split('/\R/', $pengaturan['misi']) as $poin)
                    @if (trim($poin) !== '')
                        <li class="flex gap-2">
                            <span class="text-blue-600 font-bold shrink-0">•</span>
                            <span class="leading-relaxed">{{ trim($poin) }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">-</p>
        @endif
    </div>
</div>

{{-- Sambutan --}}
@if(! empty($pengaturan['sambutan']))
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Sambutan</h2>

        <div class="flex flex-col md:flex-row gap-6">
            <div class="shrink-0">
                <div class="w-20 h-20 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-bold">
                    {{ mb_substr($pengaturan['sambutan_nama'] ?? 'P', 0, 1) }}
                </div>
            </div>
            <div>
                <p class="text-gray-600 leading-relaxed italic">{{ $pengaturan['sambutan'] }}</p>
                <div class="mt-4">
                    <div class="font-semibold text-gray-800">{{ $pengaturan['sambutan_nama'] ?? '-' }}</div>
                    <div class="text-sm text-gray-500">{{ $pengaturan['sambutan_jabatan'] ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Angka campuses --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white shadow rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-blue-700">{{ $fakultas->count() }}</div>
        <div class="text-sm text-gray-600 mt-1">Fakultas</div>
    </div>
    <div class="bg-white shadow rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-blue-700">{{ $prodi }}</div>
        <div class="text-sm text-gray-600 mt-1">Program Studi</div>
    </div>
    <div class="bg-white shadow rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-blue-700">{{ $fakultas->sum('prodi_count') }}</div>
        <div class="text-sm text-gray-600 mt-1">Program Studi Terdaftar</div>
    </div>
    <div class="bg-white shadow rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-blue-700">S1</div>
        <div class="text-sm text-gray-600 mt-1">Jenjang offerings</div>
    </div>
</div>

{{-- Daftar fakultas --}}
<div class="bg-white shadow rounded-xl p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Fakultas</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($fakultas as $f)
            <a href="{{ route('public.fakultas.detail', $f) }}"
                class="block border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:bg-blue-50/40 transition">
                <div class="font-semibold text-gray-800">{{ $f->nama }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ $f->prodi_count }} program studi</div>
            </a>
        @endforeach
    </div>
</div>
@endsection
