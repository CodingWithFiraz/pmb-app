@extends('layouts.app')
@section('title', 'Pertanyaan Umum')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Header halaman: teks tanpa container --}}
        <div class="mb-10 max-w-2xl">
            <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                Pertanyaan Umum
            </h1>
            <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Jawaban atas pertanyaan yang sering diajukan calon mahasiswa.
            </p>
        </div>

        <div class="space-y-10">
            @forelse($faq as $kategori => $items)
                <div>
                    <div class="mb-4">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-[#4E566E]">{{ $kategori }}</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($items as $item)
                            <details class="glass-card-jalur group overflow-hidden rounded-2xl transition duration-200">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-heading text-base font-bold text-[#14141F] transition hover:text-[#4E566E] sm:px-7 sm:text-lg">
                                    <span>{{ $item->pertanyaan }}</span>
                                    <span class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] text-lg font-bold leading-none text-white transition-transform duration-200 group-open:rotate-45" aria-hidden="true">+</span>
                                </summary>
                                <div class="border-t border-white/75 px-6 pt-4 pb-6 text-base leading-relaxed text-[#3E465C] sm:px-7">
                                    {{ $item->jawaban }}
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="glass-card-jalur rounded-2xl p-7 text-base text-[#3E465C]">
                    Belum ada pertanyaan yang tersedia.
                </div>
            @endforelse
        </div>

        {{-- Ajakan hubungi panitia --}}
        <div class="glass-card-jalur mt-12 rounded-2xl p-7 sm:p-10">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <h2 class="font-heading text-2xl font-bold leading-tight tracking-[-0.02em] text-[#14141F]">
                        Belum menemukan jawabannya?
                    </h2>
                    <p class="text-base leading-relaxed text-[#3E465C]">
                        Tim panitia PMB siap membantu menjawab pertanyaan Anda seputar pendaftaran dan program studi.
                    </p>
                </div>
                <a href="{{ route('public.bantuan.kontak') }}"
                   class="inline-flex h-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-6 text-base font-semibold text-white shadow-xs transition duration-150 hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#BF8AFC] focus-visible:ring-offset-2">
                    Hubungi Panitia PMB &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
