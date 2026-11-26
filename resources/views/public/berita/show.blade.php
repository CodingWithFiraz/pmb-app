@extends('layouts.app')
@section('title', $berita->judul)
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-10 pb-14 lg:pt-12 lg:pb-20">
    <div class="max-w-4xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="mb-8 flex items-center gap-1.5 text-sm text-[#4E566E]">
            <a href="{{ route('home') }}" class="shrink-0 transition hover:text-[#14141F]">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('public.berita') }}" class="shrink-0 transition hover:text-[#14141F]">Berita</a>
            <span aria-hidden="true">/</span>
            <span class="truncate font-medium text-[#14141F]">{{ $berita->judul }}</span>
        </nav>

        <article class="glass-card-jalur overflow-hidden rounded-2xl">
            @if($berita->image_url)
                <div class="h-64 w-full overflow-hidden bg-[#F1EFF3] sm:h-80">
                    <img src="{{ $berita->image_url }}" alt="{{ $berita->judul }}" class="h-full w-full object-cover">
                </div>
            @endif

            <div class="p-7 sm:p-10">
                <div class="text-sm font-medium text-[#3E465C]">
                    {{ $berita->published_at?->translatedFormat('d F Y') }}@if($berita->author) &middot; {{ $berita->author }}@endif
                </div>

                <h1 class="mt-3 font-heading text-3xl font-extrabold leading-[1.12] tracking-[-0.025em] text-[#14141F] sm:text-4xl">
                    {{ $berita->judul }}
                </h1>

                <div class="mt-6 text-base leading-relaxed text-[#3E465C] whitespace-pre-line">
                    {{ $berita->deskripsi }}
                </div>
            </div>
        </article>

        <div class="mt-8">
            <a href="{{ route('public.berita') }}"
               class="inline-flex items-center gap-1.5 text-base font-semibold text-[#4E566E] transition hover:text-[#14141F] hover:underline">
                <span aria-hidden="true">&larr;</span> Kembali ke daftar berita
            </a>
        </div>
    </div>
</section>
@endsection
