@extends('layouts.public')
@section('title', 'Fakultas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Fakultas</h1>
    <p class="text-gray-600 mt-1">Daftar fakultas yang menaungi seluruh program studi di {{ $pengaturan['nama_singkat'] ?? 'PNM Jakarta' }}.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @forelse($fakultas as $f)
        <a href="{{ route('public.fakultas.detail', $f) }}"
            class="block bg-white shadow rounded-xl p-6 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $f->kode }}</span>
                    <h2 class="font-bold text-lg mt-2 text-gray-800">{{ $f->nama }}</h2>
                    @if($f->deskripsi)
                        <p class="text-gray-600 text-sm mt-2 leading-relaxed">{{ $f->deskripsi }}</p>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    <div class="text-2xl font-bold text-blue-700">{{ $f->prodi_count }}</div>
                    <div class="text-xs text-gray-500">Prodi</div>
                </div>
            </div>

            <div class="mt-4 text-sm text-blue-600 font-medium">Lihat program studi →</div>
        </a>
    @empty
        <p class="text-gray-500 col-span-2">Belum ada data fakultas.</p>
    @endforelse
</div>
@endsection
