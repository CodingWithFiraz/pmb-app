@extends('layouts.public')
@section('title', 'Berita')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Berita</h1>
    <p class="text-gray-600 mt-1">Informasi dan pengumuman terbaru seputar PNM Jakarta.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($berita as $item)
        <a href="{{ route('public.berita.detail', $item) }}"
            class="block bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition">
            @if($item->image_url)
                <img src="{{ $item->image_url }}" alt="{{ $item->judul }}" class="w-full h-40 object-cover">
            @else
                <div class="w-full h-40 bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white text-4xl font-bold">
                    {{ mb_substr($item->judul, 0, 1) }}
                </div>
            @endif

            <div class="p-5">
                <div class="text-xs text-gray-400 mb-1">
                    {{ $item->published_at?->translatedFormat('d F Y') }} · {{ $item->author }}
                </div>
                <h2 class="font-bold text-gray-800 leading-snug">{{ $item->judul }}</h2>
                <p class="text-gray-600 text-sm mt-2 line-clamp-3">{{ $item->deskripsi }}</p>
                <div class="text-sm text-blue-600 font-medium mt-3">Baca selengkapnya →</div>
            </div>
        </a>
    @empty
        <p class="text-gray-500 col-span-3">Belum ada berita yang dipublikasikan.</p>
    @endforelse
</div>

<div class="mt-8">{{ $berita->links() }}</div>
@endsection
