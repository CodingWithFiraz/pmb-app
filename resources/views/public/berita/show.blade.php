@extends('layouts.public')
@section('title', $berita->judul)

@section('content')
<nav class="text-sm text-gray-500 mb-4">
    <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('public.berita') }}" class="hover:text-blue-600">Berita</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 truncate">{{ $berita->judul }}</span>
</nav>

<article class="bg-white shadow rounded-xl overflow-hidden">
    @if($berita->image_url)
        <img src="{{ $berita->image_url }}" alt="{{ $berita->judul }}" class="w-full h-64 object-cover">
    @endif

    <div class="p-6 md:p-8">
        <div class="text-sm text-gray-400 mb-2">
            {{ $berita->published_at?->translatedFormat('d F Y') }} · {{ $berita->author }}
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight">{{ $berita->judul }}</h1>

        <div class="mt-4 text-gray-700 leading-relaxed whitespace-pre-line">{{ $berita->deskripsi }}</div>
    </div>
</article>

<div class="mt-6">
    <a href="{{ route('public.berita') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke daftar berita</a>
</div>
@endsection
