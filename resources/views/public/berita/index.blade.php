@extends('layouts.app')
@section('title', 'Berita')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Header halaman: teks tanpa container --}}
        <div class="mb-10 max-w-2xl">
            <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                Berita
            </h1>
            <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Informasi dan pengumuman terbaru seputar PNM Jakarta.
            </p>
        </div>

        {{-- Card berita mengikuti desain section "Berita Terbaru" di halaman utama --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($berita as $item)
                <a href="{{ route('public.berita.detail', $item) }}"
                   class="glass-card-jalur group flex h-full flex-col overflow-hidden rounded-2xl transition duration-200 hover:-translate-y-0.5">
                    @if($item->image_url)
                        <div class="h-48 w-full overflow-hidden bg-[#F1EFF3]">
                            <img src="{{ $item->image_url }}" alt="{{ $item->judul }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        </div>
                    @else
                        <div class="flex h-36 items-end bg-gradient-to-br from-[#BF8AFC] to-[#FA7F94] p-6 text-white">
                            <span class="text-[13px] font-semibold text-white/95">{{ $item->author }}</span>
                        </div>
                    @endif

                    <div class="flex flex-1 flex-col p-6">
                        <div class="text-[13px] font-medium text-[#3E465C]">
                            {{ $item->published_at?->translatedFormat('d F Y') }}@if($item->author) &middot; {{ $item->author }}@endif
                        </div>
                        <h2 class="mt-2 font-heading text-xl font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#4E566E]">
                            {{ $item->judul }}
                        </h2>
                        <p class="mt-3 flex-1 text-base leading-relaxed text-[#3E465C]">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi, 120) }}
                        </p>
                        <div class="mt-5 text-[15px] font-semibold text-[#4E566E]">
                            Baca berita <span aria-hidden="true">&rarr;</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="glass-card-jalur rounded-2xl p-7 text-base text-[#3E465C] md:col-span-2 lg:col-span-3">
                    Belum ada berita yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @if($berita->hasPages())
            <div class="mt-10">
                {{ $berita->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
