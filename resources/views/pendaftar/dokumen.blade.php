@extends('layouts.pendaftar')
@section('title', 'Dokumen Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Ringkasan pendaftaran --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Dokumen Pendaftaran</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Persyaratan dokumen untuk jalur
                    <strong>{{ $pendaftaran->jalur?->nama ?? '-' }}</strong>
                    @if($pendaftaran->prodi)
                        &middot; {{ $pendaftaran->prodi->nama }}
                    @endif
                </p>
            </div>

            <div class="text-right text-sm">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $pendaftaran->isDraft() ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $pendaftaran->statusLabel() }}
                </span>
                @if($pendaftaran->nomor_pendaftaran)
                    <div class="mt-2 font-mono text-xs text-gray-600">{{ $pendaftaran->nomor_pendaftaran }}</div>
                @endif
            </div>
        </div>

        @if(! $bolehUpload)
            <p class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Dokumen terkunci karena pendaftaran sudah berstatus
                <strong>{{ $pendaftaran->statusLabel() }}</strong>.
                @if($pendaftaran->status === 'ditolak')
                    Silakan unggah ulang dokumen yang ditolak lalu ajukan verifikasi kembali.
                @endif
            </p>
        @endif

        @if($pendaftaran->catatan_admin)
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <strong>Catatan panitia:</strong> {{ $pendaftaran->catatan_admin }}
            </div>
        @endif
    </div>

    {{-- Daftar dokumen --}}
    <div class="space-y-4 mb-6">
        @forelse($dokumen as $item)
            @php
                $master = $item['master'];
                $upload = $item['upload'];
                $status = $item['status'];
            @endphp

            <div class="bg-white shadow rounded-xl p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            {{ $master->nama }}
                            @if($item['wajib'])
                                <span class="ml-1 rounded bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-600">Wajib</span>
                            @else
                                <span class="ml-1 rounded bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600">Opsional</span>
                            @endif
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Kode {{ $master->kode }} &middot; Maksimal {{ $master->max_size }} KB &middot; Format JPG, PNG, atau PDF
                        </p>
                    </div>

                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                        @if($status === 'valid')
                            bg-green-100 text-green-700
                        @elseif($status === 'menunggu')
                            bg-amber-100 text-amber-700
                        @elseif($status === 'ditolak')
                            bg-red-100 text-red-700
                        @else
                            bg-gray-100 text-gray-600
                        @endif">
                        {{ $item['label'] }}
                    </span>
                </div>

                @if($upload && $upload->file_path)
                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                        <span class="text-gray-600">Berkas: {{ basename($upload->file_path) }}</span>
                        <a href="{{ route('pendaftar.dokumen.download', $master) }}"
                            class="text-blue-600 hover:underline">Unduh berkas</a>
                    </div>
                @endif

                @if($upload && $upload->catatan_admin)
                    <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <strong>Catatan panitia:</strong> {{ $upload->catatan_admin }}
                    </div>
                @endif

                @if($bolehUpload)
                    <form method="POST" action="{{ route('pendaftar.dokumen.upload', $master) }}"
                        enctype="multipart/form-data" class="mt-4 flex flex-wrap items-end gap-3">
                        @csrf

                        <div class="flex-1 min-w-[220px]">
                            <label for="file-{{ $master->id }}" class="block text-sm font-medium text-gray-700">
                                {{ $upload ? 'Ganti berkas' : 'Unggah berkas' }}
                            </label>
                            <input type="file" id="file-{{ $master->id }}" name="file"
                                accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm text-gray-600
                                    file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-blue-700">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            {{ $upload ? 'Ganti' : 'Upload' }}
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Belum ada dokumen yang dipersyaratkan untuk jalur ini.</p>
        @endforelse
    </div>

    {{-- Ajukan verifikasi --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="font-bold text-lg">Ajukan Verifikasi</h3>

        @if($bolehAjukan)
            <p class="mt-2 text-sm text-gray-600">
                Semua dokumen wajib sudah diunggah. Setelah diajukan, jalur dan program studi terkunci
                dan pendaftaran menunggu pemeriksaan panitia.
            </p>

            <form method="POST" action="{{ route('pendaftar.dokumen.verifikasi') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white hover:bg-green-700">
                    Ajukan Verifikasi
                </button>
            </form>
        @else
            <p class="mt-2 text-sm text-gray-600">
                Tombol pengajuan aktif setelah semua dokumen wajib berstatus
                <strong>Menunggu Verifikasi</strong> atau <strong>Valid</strong>.
            </p>

            @if($dokumen->filter(fn ($item): bool => $item['wajib'] && ! $item['lengkap'])->isNotEmpty())
                <ul class="mt-3 space-y-1 text-sm text-red-700">
                    @foreach($dokumen->filter(fn ($item): bool => $item['wajib'] && ! $item['lengkap']) as $item)
                        <li>- {{ $item['master']->nama }} ({{ $item['label'] }})</li>
                    @endforeach
                </ul>
            @endif
        @endif
    </div>
</div>
@endsection
