@extends('layouts.admin')
@section('title', 'Detail Pendaftar')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F]">Detail Pendaftar</h1>
        <a href="{{ route('admin.pendaftaran.index') }}" class="text-[#7E22CE] hover:underline text-sm">&larr; Kembali</a>
    </div>

    {{-- Header --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-4">
        <div class="flex justify-between gap-4">
            <div>
                <div class="text-2xl font-bold">{{ $pendaftaran->user->name }}</div>
                <div class="text-[#4E566E]">
                    {{ $pendaftaran->prodi->nama }} &middot; {{ $pendaftaran->periode->nama }}
                </div>
                @if($pendaftaran->nomor_pendaftaran)
                    <div class="font-mono text-[#7E22CE] text-sm mt-1">{{ $pendaftaran->nomor_pendaftaran }}</div>
                @endif
            </div>
            <div class="text-right">
                <span class="px-4 py-1 rounded-full font-semibold
                    @if($pendaftaran->status === 'terverifikasi')
                        bg-green-100 text-green-700
                    @elseif($pendaftaran->status === 'ditolak')
                        bg-red-100 text-red-700
                    @elseif($pendaftaran->status === 'menunggu_verifikasi')
                        bg-amber-100 text-amber-700
                    @else
                        bg-gray-100 text-[#14141F]
                    @endif">
                    {{ $pendaftaran->statusLabel() }}
                </span>
                <div class="text-xs text-[#4E566E] mt-1">Kelulusan: {{ $pendaftaran->hasil_kelulusan }}</div>
            </div>
        </div>

        @if($pendaftaran->catatan_admin)
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <strong>Catatan panitia:</strong> {{ $pendaftaran->catatan_admin }}
            </div>
        @endif
    </div>

    {{-- Data Pribadi --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-4">
        <h2 class="font-bold text-[#7E22CE] mb-3">Data Pribadi</h2>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-[#4E566E]">Email</dt><dd>{{ $pendaftaran->user->email }}</dd>
            <dt class="text-[#4E566E]">No. HP</dt><dd>{{ $pendaftaran->user->phone ?? '-' }}</dd>
            <dt class="text-[#4E566E]">NIK</dt><dd>{{ $pendaftaran->user->profile?->nik ?? '-' }}</dd>
            <dt class="text-[#4E566E]">NISN</dt><dd>{{ $pendaftaran->user->profile?->nisn ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Jenis Kelamin</dt>
            <dd>{{ $pendaftaran->user->profile?->jenis_kelamin === 'L' ? 'Laki-laki' : ($pendaftaran->user->profile?->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</dd>
            <dt class="text-[#4E566E]">Tgl Lahir</dt>
            <dd>
                @if($pendaftaran->user->profile?->tanggal_lahir)
                    {{ $pendaftaran->user->profile->tanggal_lahir->format('d M Y') }} &middot; {{ $pendaftaran->user->profile->tempat_lahir }}
                @else
                    -
                @endif
            </dd>
            <dt class="text-[#4E566E]">Alamat</dt>
            <dd>
                @if($pendaftaran->user->profile?->alamat)
                    {{ $pendaftaran->user->profile->alamat }}, {{ $pendaftaran->user->profile->kota }}, {{ $pendaftaran->user->profile->provinsi }}
                @else
                    -
                @endif
            </dd>
        </dl>
    </div>

    {{-- Data Pendidikan --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-4">
        <h2 class="font-bold text-[#7E22CE] mb-3">Data Pendidikan & Orang Tua</h2>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-[#4E566E]">Sekolah</dt><dd>{{ $pendaftaran->user->profile?->asal_sekolah ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Tahun Lulus</dt><dd>{{ $pendaftaran->user->profile?->tahun_lulus ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Nama Orang Tua</dt><dd>{{ $pendaftaran->user->profile?->nama_ortu ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Pekerjaan Orang Tua</dt><dd>{{ $pendaftaran->user->profile?->pekerjaan_ortu ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Penghasilan Orang Tua</dt>
            <dd>
                @if($pendaftaran->user->profile?->penghasilan_ortu)
                    Rp {{ number_format($pendaftaran->user->profile->penghasilan_ortu, 0, ',', '.') }}
                @else
                    -
                @endif
            </dd>
            <dt class="text-[#4E566E]">Golongan UKT</dt><dd>{{ $pendaftaran->golongan->nama }}</dd>
        </dl>
    </div>

    {{-- Pendaftaran --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-4">
        <h2 class="font-bold text-[#7E22CE] mb-3">Data Pendaftaran</h2>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-[#4E566E]">Jalur Masuk</dt><dd>{{ $pendaftaran->jalur->nama }}</dd>
            <dt class="text-[#4E566E]">Program Studi</dt>
            <dd>
                {{ $pendaftaran->prodi->nama }} ({{ $pendaftaran->prodi->kode }}) &middot;
                {{ $pendaftaran->prodi->fakultas->nama }}
            </dd>
            <dt class="text-[#4E566E]">Pilihan 2</dt><dd>{{ $pendaftaran->prodiAlternatif->nama ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Periode</dt>
            <dd>{{ $pendaftaran->periode->nama }} (TA {{ $pendaftaran->periode->tahun_akademik }})</dd>
            <dt class="text-[#4E566E]">Diverifikasi Oleh</dt><dd>{{ $pendaftaran->verifier->name ?? '-' }}</dd>
            <dt class="text-[#4E566E]">Tgl Verifikasi</dt>
            <dd>{{ $pendaftaran->verified_at?->format('d/m/Y H:i') ?? '-' }}</dd>
        </dl>
    </div>

    {{-- Dokumen --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-4">
        <h2 class="font-bold text-[#7E22CE] mb-3">Dokumen Persyaratan</h2>

        <div class="space-y-3">
            @forelse($dokumen as $item)
                @php
                    $master = $item['master'];
                    $upload = $item['upload'];
                    $status = $item['status'];
                @endphp

                <div class="border rounded-lg px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <div class="font-medium text-sm">
                                {{ $master->nama }}
                                @if($item['wajib'])
                                    <span class="ml-1 rounded bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-600">Wajib</span>
                                @else
                                    <span class="ml-1 rounded bg-gray-100 px-2 py-0.5 text-xs font-semibold text-[#4E566E]">Opsional</span>
                                @endif
                            </div>
                            <div class="text-xs text-[#4E566E]">Kode {{ $master->kode }}</div>
                        </div>

                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                            @if($status === 'valid')
                                bg-green-100 text-green-700
                            @elseif($status === 'ditolak')
                                bg-red-100 text-red-700
                            @elseif($status === 'menunggu')
                                bg-amber-100 text-amber-700
                            @else
                                bg-gray-100 text-[#4E566E]
                            @endif">
                            @if($status === 'valid')
                                Valid
                            @elseif($status === 'ditolak')
                                Ditolak
                            @elseif($status === 'menunggu')
                                Menunggu Verifikasi
                            @else
                                Belum Diunggah
                            @endif
                        </span>
                    </div>

                    @if($upload)
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm">
                            <a href="{{ route('admin.dokumen.unduh', $upload) }}" class="text-[#7E22CE] hover:underline">
                                Unduh {{ basename($upload->file_path) }}
                            </a>
                        </div>

                        @if($upload->catatan_admin)
                            <div class="mt-2 rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700">
                                Catatan: {{ $upload->catatan_admin }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.dokumen.status', $upload) }}"
                            class="mt-3 flex flex-wrap items-end gap-2 border-t border-[#EFEBF4] pt-3">
                            @csrf

                            <div class="flex-1 min-w-[220px]">
                                <label for="catatan-{{ $upload->id }}" class="block text-xs font-medium text-[#4E566E]">
                                    Catatan panitia
                                </label>
                                <input type="text" id="catatan-{{ $upload->id }}" name="catatan"
                                    value="{{ old('catatan', $upload->catatan_admin) }}"
                                    class="mt-1 block w-full rounded-lg border-[#E6E1EC] text-sm"
                                    placeholder="Wajib diisi bila menolak dokumen">
                                @error('catatan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" name="status" value="valid"
                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                Tandai Valid
                            </button>
                            <button type="submit" name="status" value="ditolak"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                Tolak Dokumen
                            </button>
                        </form>
                    @else
                        <p class="mt-2 text-xs text-[#4E566E]">Pendaftar belum mengunggah dokumen ini.</p>
                    @endif
                </div>
            @empty
                <p class="text-[#4E566E] text-sm">Jalur ini tidak memiliki persyaratan dokumen.</p>
            @endforelse
        </div>
    </div>

    {{-- Aksi verifikasi pendaftaran --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-4">
        <h2 class="font-bold text-[#7E22CE] mb-3">Verifikasi Pendaftar</h2>

        @if($pendaftaran->status === 'terverifikasi')
            <p class="text-sm text-green-700">
                Pendaftaran sudah terverifikasi
                @if($pendaftaran->verifier)
                    oleh {{ $pendaftaran->verifier->name }}
                @endif
                pada {{ $pendaftaran->verified_at?->format('d/m/Y H:i') }}.
                Tagihan otomatis sudah dibuat.
            </p>
        @else
            @if($siapVerifikasi)
                <p class="text-sm text-[#4E566E] mb-3">Semua berkas persyaratan yang diunggah sudah berstatus <strong>Valid</strong>. Pendaftaran dapat diverifikasi.</p>

                <form method="POST" action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran) }}">
                    @csrf
                    <button type="submit"
                        class="rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white hover:bg-green-700 shadow-sm transition">
                        Verifikasi Pendaftar
                    </button>
                </form>
            @else
                @php
                    $pendingWajib = $dokumen->filter(fn ($item) => $item['wajib'] && $item['status'] !== 'valid');
                    $pendingUpload = $dokumen->filter(fn ($item) => ! $item['wajib'] && $item['upload'] !== null && $item['status'] !== 'valid');
                @endphp

                <p class="text-sm text-[#4E566E] mb-2">Tombol verifikasi akan aktif setelah seluruh berkas persyaratan berstatus <strong>Valid</strong>.</p>

                @if($pendingWajib->isNotEmpty() || $pendingUpload->isNotEmpty())
                    <ul class="space-y-1 text-sm text-amber-700 mb-3">
                        @foreach($pendingWajib as $item)
                            <li>- {{ $item['master']->nama }} ({{ $item['status'] === 'belum' ? 'wajib, belum diunggah' : $item['status'] }})</li>
                        @endforeach
                        @foreach($pendingUpload as $item)
                            <li>- {{ $item['master']->nama }} ({{ $item['status'] }})</li>
                        @endforeach
                    </ul>
                @elseif($dokumen->isNotEmpty() && $dokumen->every(fn ($item) => $item['upload'] === null))
                    <p class="text-sm text-amber-700 mb-3">Pendaftar belum mengunggah berkas persyaratan apapun.</p>
                @endif

                <button type="button" disabled
                    class="cursor-not-allowed rounded-lg bg-gray-300 px-5 py-2 text-sm font-semibold text-[#4E566E] shadow-sm"
                    title="Berkas belum valid">
                    Verifikasi Pendaftar
                </button>
            @endif

            <div class="mt-6 border-t border-[#EFEBF4] pt-4">
                <h3 class="font-semibold text-sm text-[#14141F]">Tolak Pendaftaran</h3>
                <p class="mt-1 text-xs text-[#4E566E]">
                    Pendaftar akan dapat merevisi dokumen dan mengajukan ulang pendaftaran.
                </p>

                <form method="POST" action="{{ route('admin.pendaftaran.tolak', $pendaftaran) }}" class="mt-3">
                    @csrf

                    <label for="catatan-tolak" class="block text-sm font-medium text-[#14141F]">Catatan penolakan</label>
                    <textarea name="catatan" id="catatan-tolak" rows="2" required
                        class="mt-1 block w-full rounded-lg border-[#E6E1EC] text-sm"
                        placeholder="Contoh: Berkas rapor kurang halaman.">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="mt-3 rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Tolak Pendaftaran
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Pembayaran / Tagihan --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <h2 class="font-bold text-[#7E22CE] mb-3">Tagihan Pembayaran</h2>
        @if($pendaftaran->pembayaran->isEmpty())
            <p class="text-[#4E566E] text-sm">Belum ada tagihan. Tagihan dibuat otomatis saat pendaftaran diverifikasi.</p>
        @else
            <div class="space-y-2">
                @foreach($pendaftaran->pembayaran as $bayar)
                    <div class="flex items-center justify-between border rounded-lg px-3 py-2 text-sm">
                        <div>
                            <div class="font-medium">{{ $bayar->jenis === 'ukt' ? 'UKT' : 'Biaya Pendaftaran' }}</div>
                            <div class="text-xs text-[#4E566E]">
                                {{ $bayar->metode ?? '-' }} &middot;
                                @if($bayar->status === 'paid')
                                    Lunas
                                @elseif($bayar->status === 'pending')
                                    Menunggu Pembayaran
                                @elseif($bayar->status === 'expired')
                                    Kedaluwarsa
                                @else
                                    {{ $bayar->status }}
                                @endif
                            </div>
                        </div>
                        <div class="font-semibold">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
