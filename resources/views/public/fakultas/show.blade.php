@extends('layouts.public')
@section('title', $fakultas->nama)

@section('content')
<nav class="text-sm text-gray-500 mb-4">
    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('public.fakultas') }}" class="hover:text-blue-600">Fakultas</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700">{{ $fakultas->nama }}</span>
</nav>

<div class="bg-white shadow rounded-xl p-6 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $fakultas->kode }}</span>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mt-2">{{ $fakultas->nama }}</h1>
            @if($fakultas->deskripsi)
                <p class="text-gray-600 mt-2 max-w-3xl leading-relaxed">{{ $fakultas->deskripsi }}</p>
            @endif
        </div>
        <div class="text-right">
            <div class="text-3xl font-bold text-blue-700">{{ $fakultas->prodi->count() }}</div>
            <div class="text-xs text-gray-500">Program Studi</div>
        </div>
    </div>
</div>

<h2 class="text-xl font-bold text-gray-800 mb-4">Program Studi</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @forelse($fakultas->prodi as $prodi)
        <a href="{{ route('public.prodi', $prodi) }}"
            class="block bg-white shadow rounded-xl p-5 hover:shadow-lg transition">
            <div class="flex justify-between items-start gap-3">
                <div>
                    <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $prodi->kode }}</span>
                    <h3 class="font-bold text-lg mt-2 text-gray-800">{{ $prodi->nama }}</h3>
                    <div class="text-sm text-gray-500">{{ $prodi->jenjang }}</div>
                    @if($prodi->deskripsi)
                        <p class="text-gray-600 text-sm mt-2 leading-relaxed">{{ $prodi->deskripsi }}</p>
                    @endif
                </div>
                @if($prodi->kuota)
                    <div class="shrink-0 text-right">
                        <div class="text-gray-400 text-xs">Kuota</div>
                        <div class="font-bold text-blue-700">{{ $prodi->kuota }}</div>
                    </div>
                @endif
            </div>
        </a>
    @empty
        <p class="text-gray-500 col-span-2">Belum ada program studi pada fakultas ini.</p>
    @endforelse
</div>
@endsection
