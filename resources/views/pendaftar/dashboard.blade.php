@extends('layouts.pendaftar')
@section('title', 'Dashboard Saya')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Sambutan & periode --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-6">
        <h2 class="text-xl font-bold text-[#14141F] mb-2">Selamat Datang, {{ auth()->user()->name }}</h2>

        @if($activePeriod)
            <p class="text-[#4E566E]">
                Periode aktif: <strong>{{ $activePeriod->nama }}</strong>
                (TA {{ $activePeriod->tahun_akademik }}) &middot;
                {{ $activePeriod->tanggal_mulai->translatedFormat('d M Y') }} - {{ $activePeriod->tanggal_selesai->translatedFormat('d M Y') }}
            </p>
        @else
            <p class="text-[#4E566E]">Saat ini belum ada periode pendaftaran yang aktif.</p>
        @endif
    </div>

    {{-- Notifikasi --}}
    @if($notifikasi->isNotEmpty())
        <div class="bg-[#F5EFFB] border border-[#E6D8FA] rounded-xl p-6 mb-6">
            <h3 class="font-bold text-lg mb-3 text-[#581C87]">Notifikasi Baru</h3>
            <ul class="space-y-2 text-sm">
                @foreach($notifikasi as $n)
                    <li class="flex items-start gap-3 bg-white px-3 py-2 rounded-lg border border-[#EFEBF4]">
                        <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#F5EFFB] text-xs font-bold text-[#7E22CE]">!</div>
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-[#14141F]">{{ $n['message'] }}</div>
                            @if($n['url'])
                                <a href="{{ $n['url'] }}" class="mt-1 inline-block text-xs font-medium text-[#4E566E] hover:text-[#14141F] hover:underline">
                                    Lihat detail &rarr;
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Kartu progres --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        {{-- Profil --}}
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-5">
            <div class="text-sm text-[#4E566E]">Profil</div>
            <div class="mt-1 text-2xl font-bold text-[#14141F]">{{ $profilPersen }}%</div>
            <div class="mt-3 h-2 w-full rounded bg-gray-100">
                <div class="h-2 rounded bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94]" style="width: {{ $profilPersen }}%"></div>
            </div>
        </div>

        {{-- Dokumen --}}
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-5">
            <div class="text-sm text-[#4E566E]">Dokumen</div>
            <div class="mt-1 text-2xl font-bold text-[#14141F]">
                {{ $dokumenWajib > 0 ? $dokumenUpload.'/'.$dokumenWajib : '-' }}
            </div>
            <div class="mt-3 text-xs text-[#4E566E]">
                {{ $dokumenWajib > 0 ? 'Dokumen terunggah dari total wajib' : 'Menunggu pilihan jalur' }}
            </div>
        </div>

        {{-- Verifikasi --}}
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-5">
            <div class="text-sm text-[#4E566E]">Status Verifikasi</div>
            <div class="mt-2">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold
                    @if($pendaftaran?->status === 'terverifikasi')
                        bg-green-100 text-green-700
                    @elseif($pendaftaran?->status === 'ditolak')
                        bg-red-100 text-red-700
                    @elseif($pendaftaran?->status === 'menunggu_verifikasi')
                        bg-amber-100 text-amber-700
                    @elseif($pendaftaran?->status === 'draft')
                        bg-[#F5EFFB] text-[#7E22CE]
                    @else
                        bg-gray-100 text-[#4E566E]
                    @endif">
                    {{ $statusVerifikasi }}
                </span>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-5">
            <div class="text-sm text-[#4E566E]">Status Pembayaran</div>
            <div class="mt-2">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $pembayaran['lunas'] ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $pembayaran['label'] }}
                </span>
            </div>
            @if($pembayaran['nominal'])
                <div class="mt-2 text-xs text-[#4E566E]">
                    Biaya: Rp {{ number_format($pembayaran['nominal'], 0, ',', '.') }}
                </div>
            @endif

            <a href="{{ route('pendaftar.pembayaran') }}" class="mt-2 inline-block text-xs text-[#4E566E] hover:text-[#14141F] hover:underline">
                Kelola pembayaran &rarr;
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Pendaftaran saya --}}
        <div class="lg:col-span-2 rounded-2xl border border-[#E6E1EC] bg-white p-6">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="font-bold text-lg">Pendaftaran Saya</h3>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('pendaftar.dokumen') }}" class="text-[#4E566E] hover:text-[#14141F] hover:underline">Dokumen &rarr;</a>
                    <a href="{{ route('pendaftar.pilihan') }}" class="text-[#4E566E] hover:text-[#14141F] hover:underline">
                        {{ $pendaftaran?->isDraft() ? 'Ubah pilihan' : 'Lihat pilihan' }} &rarr;
                    </a>
                </div>
            </div>

            @if($pendaftaran)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-[#4E566E]">Jalur masuk</dt>
                        <dd class="font-medium text-[#14141F]">{{ $pendaftaran->jalur?->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#4E566E]">Golongan UKT</dt>
                        <dd class="font-medium text-[#14141F]">{{ $pendaftaran->golongan?->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#4E566E]">Program studi pilihan 1</dt>
                        <dd class="font-medium text-[#14141F]">{{ $pendaftaran->prodi?->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#4E566E]">Program studi pilihan 2</dt>
                        <dd class="font-medium text-[#14141F]">{{ $pendaftaran->prodiAlternatif?->nama ?? 'Tidak dipilih' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#4E566E]">Perkiraan UKT / tahun</dt>
                        <dd class="font-medium text-[#14141F]">
                            {{ $ukt ? 'Rp '.number_format($ukt, 0, ',', '.') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[#4E566E]">Nomor pendaftaran</dt>
                        <dd class="font-mono text-[#14141F]">{{ $pendaftaran->nomor_pendaftaran ?? 'Belum diterbitkan' }}</dd>
                    </div>
                </dl>

                @if(! $pendaftaran->isDraft())
                    <p class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        Pilihan jalur dan program studi terkunci karena status pendaftaran
                        <strong>{{ $pendaftaran->statusLabel() }}</strong>.
                    </p>
                @endif
            @else
                <p class="text-[#4E566E]">
                    Belum ada pendaftaran pada periode ini. Mulai dengan melengkapi profil, lalu pilih jalur dan program studi.
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('pendaftar.profil') }}" class="rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-4 py-2 text-sm font-medium text-white hover:opacity-95">
                        Lengkapi Profil
                    </a>
                    <a href="{{ route('pendaftar.pilihan') }}" class="rounded-lg border border-[#BF8AFC] px-4 py-2 text-sm font-medium text-[#4E566E] hover:text-[#14141F] hover:bg-[#F5EFFB]">
                        Pilih Jalur
                    </a>
                </div>
            @endif
        </div>

        {{-- Checklist --}}
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
            <h3 class="font-bold text-lg mb-4">Checklist Pendaftaran</h3>

            <ul class="space-y-3">
                @foreach($checklist as $item)
                    <li class="flex items-start gap-3">
                        @if($item['selesai'])
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-700">&#10003;</span>
                        @else
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-[#E6E1EC] text-xs text-gray-400">&#8226;</span>
                        @endif

                        <div class="min-w-0">
                            <div class="text-sm font-medium {{ $item['selesai'] ? 'text-[#14141F]' : 'text-[#14141F]' }}">
                                {{ $item['label'] }}
                            </div>
                            <div class="text-xs text-[#4E566E]">{{ $item['keterangan'] }}</div>

                            @if(! $item['selesai'] && $item['url'])
                                <a href="{{ $item['url'] }}" class="mt-1 inline-block text-xs font-medium text-[#4E566E] hover:text-[#14141F] hover:underline">
                                    {{ $item['aksi'] }} &rarr;
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
