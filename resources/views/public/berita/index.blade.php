@extends('layouts.app')
@section('title', 'Berita')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
@php
    $slides  = $berita->take(5);
    $populer = $berita->first();
@endphp

{{-- ===== 1. FULL-WIDTH HERO SLIDER ALA KEMNAKER (dengan aksen warna PMB) ===== --}}
@if($slides->isNotEmpty())
    <section aria-label="Sorotan Berita Utama" class="relative w-full overflow-hidden bg-[#14141F]">
        <div id="berita-hero-slider" class="relative h-[380px] sm:h-[440px] lg:h-[480px] w-full">
            @foreach($slides as $idx => $slide)
                <article data-hero-slide="{{ $idx }}"
                         class="berita-slide absolute inset-0 transition-opacity duration-500 ease-out {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}">
                    {{-- Background Image dengan Progressive Blur-Up 400ms --}}
                    @if($slide->image_url)
                        <img src="{{ $slide->image_url }}"
                             alt="{{ $slide->judul }}"
                             loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                             decoding="async"
                             onload="this.classList.add('is-loaded')"
                             class="img-blur-up absolute inset-0 h-full w-full object-cover object-center">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-[#9D80EC] via-[#BF8AFC] to-[#FA7F94]"></div>
                    @endif

                    {{-- Dark & Lavender-Tinted Gradient Overlay ala Kemnaker (gelap pekat di kiri untuk teks, transparan ke kanan untuk foto) --}}
                    <div class="
                        absolute inset-0
                        bg-gradient-to-r from-[#14141F]/95 via-[#14141F]/80 to-[#14141F]/25
                    " aria-hidden="true"></div>
                    <div class="
                        absolute inset-0
                        bg-[radial-gradient(ellipse_55%_70%_at_12%_85%,rgba(191,138,252,0.22)_0%,transparent_70%)]
                    " aria-hidden="true"></div>

                    {{-- Teks Hero di sisi kiri --}}
                    <div class="relative z-10 mx-auto flex h-full max-w-7xl items-center px-6 sm:px-8 lg:px-12">
                        <div class="max-w-xl pb-10 text-white">
                            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-wide text-[#E4E7FF] backdrop-blur-md">
                                <span class="size-1.5 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]"></span>
                                <span>{{ $slide->author ?: 'Redaksi PNM' }}</span>
                                @if($slide->published_at)
                                    <span aria-hidden="true">&middot;</span>
                                    <span>{{ $slide->published_at->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>

                            <h1 class="font-heading text-2xl font-extrabold leading-[1.18] tracking-[-0.025em] text-white sm:text-3xl lg:text-4xl">
                                <a href="{{ route('public.berita.detail', $slide) }}" class="hover:underline decoration-[#BF8AFC] underline-offset-4">
                                    {{ $slide->judul }}
                                </a>
                            </h1>

                            <p class="mt-4 line-clamp-3 text-sm leading-relaxed text-white/85 sm:text-base">
                                {{ \Illuminate\Support\Str::limit($slide->deskripsi, 210) }}
                            </p>
                        </div>
                    </div>
                </article>
            @endforeach

            {{-- Indicator Dots di Kiri Bawah (Persis posisi Kemnaker) --}}
            <div class="pointer-events-none absolute inset-x-0 bottom-7 z-20">
                <div class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-12">
                    <div class="pointer-events-auto inline-flex items-center gap-2.5" role="tablist" aria-label="Pilih slide berita">
                        @foreach($slides as $idx => $slide)
                            <button type="button"
                                    data-hero-dot="{{ $idx }}"
                                    aria-label="Slide berita {{ $idx + 1 }}"
                                    aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                                    class="berita-dot size-2.5 rounded-full transition-all duration-300 cursor-pointer {{ $idx === 0 ? 'w-6 bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] scale-105' : 'bg-white/40 hover:bg-white/70' }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- ===== 2. SECTION "BERITA TERPOPULER" ALA KEMNAKER (dengan palet & glass card PMB) ===== --}}
<section class="w-full bg-[#F8F6F9] pt-12 pb-16 lg:pt-16 lg:pb-24">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

        {{-- Header Berita Terpopuler + Tombol di Kanan --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="hero-enter font-heading text-3xl font-extrabold leading-[1.12] tracking-[-0.03em] text-[#14141F] sm:text-4xl">
                    Berita Terpopuler
                </h2>
                <p class="hero-enter-delay-1 mt-2 max-w-xl text-sm leading-relaxed text-[#3E465C] sm:text-base">
                    Temukan informasi dan berita paling populer seputar Penerimaan Mahasiswa Baru Politeknik Negeri Media Banten
                </p>
            </div>

            <a href="#daftar-berita-lainnya"
               class="inline-flex h-10 w-fit shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-5 text-sm font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5">
                Lihat lebih banyak
            </a>
        </div>

        {{-- Card Utama Berita Terpopuler (Horizontal 2 Kolom: Foto Kiri, Konten Kanan ala Kemnaker) --}}
        @if($populer)
            <div class="mb-14">
                <a href="{{ route('public.berita.detail', $populer) }}"
                   class="glass-card-jalur group grid grid-cols-1 overflow-hidden rounded-2xl transition duration-200 hover:-translate-y-0.5 lg:grid-cols-12">
                    {{-- Kolom Kiri: Gambar besar --}}
                    <div class="h-64 w-full overflow-hidden bg-[#F1EFF3] sm:h-80 lg:col-span-6 lg:h-full lg:min-h-[340px]">
                        @if($populer->image_url)
                            <img src="{{ $populer->image_url }}"
                                 alt="{{ $populer->judul }}"
                                 loading="lazy"
                                 decoding="async"
                                 onload="this.classList.add('is-loaded')"
                                 class="img-blur-up h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]">
                        @else
                            <div class="flex h-full min-h-64 items-end bg-gradient-to-br from-[#BF8AFC] to-[#FA7F94] p-8 text-white">
                                <span class="text-sm font-semibold text-white/95">{{ $populer->author }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Penulis & Hari/Tanggal di atas, Judul, lalu Paragraf --}}
                    <div class="flex flex-col justify-center p-7 sm:p-9 lg:col-span-6 lg:p-10">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs sm:text-[13px]">
                            <span class="font-bold text-[#7E22CE]">
                                {{ $populer->author ?: 'Redaksi PNM' }}
                            </span>
                            @if($populer->published_at)
                                <span class="font-medium text-[#4E566E]">
                                    {{ $populer->published_at->translatedFormat('l, d F Y') }}
                                </span>
                            @endif
                        </div>

                        <h3 class="mt-2.5 font-heading text-xl font-extrabold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#7E22CE] sm:text-2xl">
                            {{ \Illuminate\Support\Str::limit($populer->judul, 75) }}
                        </h3>

                        <p class="mt-3.5 text-sm leading-relaxed text-[#3E465C] sm:text-[15px]">
                            {{ \Illuminate\Support\Str::limit($populer->deskripsi, 340) }}
                        </p>

                        <div class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-[#7E22CE]">
                            <span>Baca selengkapnya</span>
                            <span aria-hidden="true">&rarr;</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        {{-- Daftar Berita Lainnya (Grid 3 Kolom dengan gaya PMB) --}}
        <div id="daftar-berita-lainnya" class="pt-2">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="font-heading text-2xl font-bold tracking-tight text-[#14141F]">
                    Berita Terbaru Lainnya
                </h3>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($berita as $item)
                    <a href="{{ route('public.berita.detail', $item) }}"
                       class="glass-card-jalur group flex h-full flex-col overflow-hidden rounded-2xl transition duration-200 hover:-translate-y-0.5">
                        @if($item->image_url)
                            <div class="h-48 w-full overflow-hidden bg-[#F1EFF3]">
                                <img src="{{ $item->image_url }}" alt="{{ $item->judul }}"
                                     loading="lazy" decoding="async"
                                     onload="this.classList.add('is-loaded')"
                                     class="img-blur-up h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                            </div>
                        @else
                            <div class="flex h-36 items-end bg-gradient-to-br from-[#BF8AFC] to-[#FA7F94] p-6 text-white">
                                <span class="text-[13px] font-semibold text-white/95">{{ $item->author }}</span>
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1 text-xs">
                                @if($item->author)
                                    <span class="font-bold text-[#7E22CE]">{{ \Illuminate\Support\Str::limit($item->author, 32) }}</span>
                                @endif
                                <span class="font-medium text-[#4E566E]">
                                    {{ $item->published_at?->translatedFormat('l, d F Y') }}
                                </span>
                            </div>

                            <h4 class="mt-2 font-heading text-lg font-bold leading-snug tracking-tight text-[#14141F] transition group-hover:text-[#7E22CE] sm:text-xl">
                                {{ $item->judul }}
                            </h4>

                            <p class="mt-3 flex-1 text-sm leading-relaxed text-[#3E465C] sm:text-[15px]">
                                {{ \Illuminate\Support\Str::limit($item->deskripsi, 130) }}
                            </p>

                            <div class="mt-5 text-sm font-semibold text-[#7E22CE]">
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
        </div>

        @if($berita->hasPages())
            <div class="mt-10">
                {{ $berita->links() }}
            </div>
        @endif
    </div>
</section>

@if($slides->count() > 1)
<script>
    (function () {
        const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
        const dots   = Array.from(document.querySelectorAll('[data-hero-dot]'));
        if (slides.length <= 1) return;

        let activeIndex = 0;
        let timer = null;

        function showSlide(index) {
            activeIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, idx) => {
                const isActive = idx === activeIndex;
                slide.classList.toggle('opacity-100', isActive);
                slide.classList.toggle('z-10', isActive);
                slide.classList.toggle('opacity-0', !isActive);
                slide.classList.toggle('z-0', !isActive);
                slide.classList.toggle('pointer-events-none', !isActive);
            });

            dots.forEach((dot, idx) => {
                const isActive = idx === activeIndex;
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
                dot.className = isActive
                    ? 'berita-dot size-2.5 w-6 rounded-full bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] scale-105 transition-all duration-300 cursor-pointer'
                    : 'berita-dot size-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300 cursor-pointer';
            });
        }

        function startAutoSlide() {
            stopAutoSlide();
            timer = window.setInterval(() => {
                showSlide(activeIndex + 1);
            }, 5500);
        }

        function stopAutoSlide() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                showSlide(idx);
                startAutoSlide();
            });
        });

        startAutoSlide();
    })();
</script>
@endif
@endsection
