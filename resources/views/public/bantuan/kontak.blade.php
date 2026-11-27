@extends('layouts.app')
@section('title', 'Hubungi Kami')
@section('body-class', '!bg-[#F8F6F9]')

@section('full-width-content')
<section class="w-full bg-[#F8F6F9] pt-12 pb-14 lg:pt-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        {{-- Header halaman: teks tanpa container --}}
        <div class="mb-10 max-w-2xl">
            <h1 class="hero-enter font-heading text-4xl font-extrabold leading-[1.08] tracking-[-0.035em] text-[#14141F] sm:text-5xl">
                Hubungi Kami
            </h1>
            <p class="hero-enter-delay-1 mt-4 text-base leading-relaxed text-[#3E465C] sm:text-lg">
                Informasi kontak panitia PMB {{ $pengaturan['nama_singkat'] ?? 'Politeknik Negeri Media Banten' }}.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Detail kontak --}}
            <article class="glass-card-jalur rounded-2xl p-7 sm:p-8 lg:col-span-5">
                <div class="text-sm font-semibold uppercase tracking-wider text-[#4E566E]">Informasi Layanan</div>
                <h2 class="mt-2 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F]">
                    Kontak Panitia
                </h2>

                <dl class="mt-6 divide-y divide-white/75 text-base">
                    <div class="py-3.5 first:pt-0">
                        <dt class="text-[13px] font-semibold uppercase tracking-wide text-[#4E566E]">Kampus</dt>
                        <dd class="mt-1 font-heading font-bold text-[#14141F]">{{ $pengaturan['nama_kampus'] ?? '-' }}</dd>
                    </div>
                    <div class="py-3.5">
                        <dt class="text-[13px] font-semibold uppercase tracking-wide text-[#4E566E]">Alamat</dt>
                        <dd class="mt-1 leading-relaxed text-[#3E465C]">{{ $pengaturan['alamat_kampus'] ?? '-' }}</dd>
                    </div>
                    <div class="py-3.5">
                        <dt class="text-[13px] font-semibold uppercase tracking-wide text-[#4E566E]">Email</dt>
                        <dd class="mt-1">
                            @if(! empty($pengaturan['email_kampus']))
                                <a href="mailto:{{ $pengaturan['email_kampus'] }}" class="font-semibold text-[#4E566E] hover:text-[#14141F] hover:underline">
                                    {{ $pengaturan['email_kampus'] }}
                                </a>
                            @else
                                <span class="text-[#3E465C]">-</span>
                            @endif
                        </dd>
                    </div>
                    <div class="py-3.5">
                        <dt class="text-[13px] font-semibold uppercase tracking-wide text-[#4E566E]">Telepon</dt>
                        <dd class="mt-1 font-numeric font-medium tabular-nums text-[#3E465C]">{{ $pengaturan['telepon_kampus'] ?? '-' }}</dd>
                    </div>
                    <div class="py-3.5">
                        <dt class="text-[13px] font-semibold uppercase tracking-wide text-[#4E566E]">WhatsApp</dt>
                        <dd class="mt-1 font-numeric font-medium tabular-nums text-[#3E465C]">{{ $pengaturan['wa_kampus'] ?? '-' }}</dd>
                    </div>
                    <div class="py-3.5 last:pb-0">
                        <dt class="text-[13px] font-semibold uppercase tracking-wide text-[#4E566E]">Jam Operasional</dt>
                        <dd class="mt-1 text-[#3E465C]">{{ $pengaturan['jam_operasional'] ?? '-' }}</dd>
                    </div>
                </dl>
            </article>

            {{-- Peta --}}
            <article class="glass-card-jalur flex flex-col overflow-hidden rounded-2xl lg:col-span-7">
                <div class="p-7 pb-5 sm:p-8 sm:pb-6">
                    <div class="text-sm font-semibold uppercase tracking-wider text-[#4E566E]">Peta</div>
                    <h2 class="mt-2 font-heading text-2xl font-bold leading-snug tracking-tight text-[#14141F]">
                        Lokasi Kampus
                    </h2>
                </div>

                @if(! empty($pengaturan['maps_embed']))
                    <div class="min-h-96 flex-1 w-full border-t border-white/75">
                        <iframe
                            src="{{ $pengaturan['maps_embed'] }}"
                            class="h-full min-h-96 w-full"
                            style="border:0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Peta lokasi {{ $pengaturan['nama_kampus'] ?? 'kampus' }}"></iframe>
                    </div>
                @else
                    <div class="flex min-h-96 flex-1 w-full items-center justify-center border-t border-white/75 bg-white/40 text-base text-[#4E566E]">
                        Peta lokasi belum tersedia.
                    </div>
                @endif
            </article>
        </div>
    </div>
</section>
@endsection
