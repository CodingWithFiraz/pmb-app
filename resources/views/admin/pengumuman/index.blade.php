@extends('layouts.admin')
@section('title', 'Pengumuman Kelulusan')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6" data-dt2-root>
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">Pengumuman Kelulusan</h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">Tetapkan hasil seleksi kelulusan pendaftar secara satuan maupun massal.</p>
        </div>
        <a href="{{ route('admin.pendaftaran.index') }}"
           class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl border border-[#E6E1EC] bg-white px-4 text-sm font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:bg-[#FAF8FC]">
            &larr; Kembali ke Daftar Pendaftar
        </a>
    </div>

    {{-- Filter pencarian --}}
    <form method="GET" class="flex flex-wrap items-center gap-2.5 rounded-2xl border border-[#E6E1EC] bg-white p-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / nomor / email..."
               class="h-9 min-w-48 flex-1 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] placeholder:text-[#818CA2] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">

        <select name="periode_id" aria-label="Filter periode"
                class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
            <option value="">Semua Periode</option>
            @foreach($periodeList as $p)
                <option value="{{ $p->id }}" {{ (int) request('periode_id') === $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>

        <select name="jalur_id" aria-label="Filter jalur"
                class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
            <option value="">Semua Jalur</option>
            @foreach($jalurList as $j)
                <option value="{{ $j->id }}" {{ (int) request('jalur_id') === $j->id ? 'selected' : '' }}>
                    {{ $j->kode }} - {{ $j->nama }}
                </option>
            @endforeach
        </select>

        <select name="status_bayar" aria-label="Filter status bayar"
                class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
            <option value="">Semua Status Bayar</option>
            <option value="paid" {{ request('status_bayar') === 'paid' ? 'selected' : '' }}>Lunas</option>
            <option value="pending" {{ request('status_bayar') === 'pending' ? 'selected' : '' }}>Belum Lunas</option>
        </select>

        <select name="hasil_kelulusan" aria-label="Filter hasil kelulusan"
                class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
            <option value="">Semua Hasil</option>
            <option value="belum" {{ request('hasil_kelulusan') === 'belum' ? 'selected' : '' }}>Belum Dinyatakan</option>
            <option value="lulus" {{ request('hasil_kelulusan') === 'lulus' ? 'selected' : '' }}>Lulus</option>
            <option value="tidak_lulus" {{ request('hasil_kelulusan') === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
        </select>

        <button type="submit"
                class="inline-flex h-9 items-center justify-center rounded-lg bg-black px-4 text-sm font-semibold text-white transition hover:bg-[#14141F]/85">
            Filter
        </button>
        <a href="{{ route('admin.pengumuman.index') }}"
           class="inline-flex h-9 items-center justify-center rounded-lg border border-[#E6E1EC] bg-white px-3.5 text-sm font-medium text-[#4E566E] transition hover:bg-[#FAF8FC] hover:text-[#14141F]">
            Reset
        </a>
    </form>

    {{-- DataTable 2: Density selector + tabel seragam --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between gap-3">
            <div class="inline-flex items-center gap-2.5">
                <label for="dt2-density-pengumuman" class="text-xs font-medium text-[#4E566E]">Kerapatan</label>
                <select id="dt2-density-pengumuman" data-dt2-density aria-label="Pilih kerapatan tabel"
                        class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                    <option value="compact">Compact</option>
                    <option value="standard" selected>Standard</option>
                    <option value="flexible">Flexible</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#E6E1EC] bg-white">
            <div class="overflow-x-auto">
                <table data-dt2-table class="w-full text-left text-sm [&_td]:py-3 [&_th]:py-2.5">
                    <thead class="border-b border-[#EFEBF4] bg-[#FAF8FC] text-xs font-semibold text-[#4E566E]">
                        <tr>
                            <th class="w-11 pl-5 pr-2">
                                <input type="checkbox" data-dt2-select-all aria-label="Pilih semua pendaftar"
                                       class="size-4 rounded border-[#D9D0E5] text-[#BF8AFC] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                            </th>
                            <th class="px-4">No. Daftar</th>
                            <th class="px-4">Nama</th>
                            <th class="px-4">Jalur</th>
                            <th class="px-4">Prodi</th>
                            <th class="px-4">Status Bayar</th>
                            <th class="px-4">Hasil Kelulusan</th>
                            <th class="pl-4 pr-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFEBF4]">
                        @forelse($pendaftaran as $p)
                            <tr class="transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]">
                                <td class="pl-5 pr-2">
                                    <input type="checkbox" data-dt2-row-check aria-label="Pilih {{ $p->user->name }}"
                                           class="size-4 rounded border-[#D9D0E5] text-[#BF8AFC] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                                </td>
                                <td class="px-4 font-mono text-xs text-[#4E566E]">{{ $p->nomor_pendaftaran ?? '-' }}</td>
                                <td class="px-4 font-semibold text-[#14141F]">{{ $p->user->name }}</td>
                                <td class="px-4">
                                    <span class="inline-flex items-center rounded-md bg-[#F5EFFB] px-2.5 py-0.5 font-mono text-xs font-bold text-[#7E22CE]">
                                        {{ $p->jalur?->kode ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 text-[#4E566E]">{{ $p->prodi?->nama ?? '-' }}</td>
                                <td class="px-4">
                                    @php
                                        $lunas = $p->pembayaran->every(fn ($t) => $t->status === 'paid') && $p->pembayaran->isNotEmpty();
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $lunas ? 'bg-emerald-600/10 text-emerald-700' : 'bg-amber-600/10 text-amber-700' }}">
                                        {{ $lunas ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        @if($p->hasil_kelulusan === 'lulus')
                                            bg-emerald-600/10 text-emerald-700
                                        @elseif($p->hasil_kelulusan === 'tidak_lulus')
                                            bg-rose-600/10 text-rose-700
                                        @else
                                            bg-slate-500/10 text-slate-600
                                        @endif">
                                        @if($p->hasil_kelulusan === 'lulus')
                                            Lulus
                                        @elseif($p->hasil_kelulusan === 'tidak_lulus')
                                            Tidak Lulus
                                        @else
                                            Belum
                                        @endif
                                    </span>
                                </td>
                                <td class="pl-4 pr-5 text-right">
                                    <form method="POST" action="{{ route('admin.pengumuman.set-hasil', $p) }}"
                                          class="inline-flex flex-wrap items-center justify-end gap-2"
                                          onsubmit="return confirm('Set hasil kelulusan untuk {{ $p->user->name }}?')">
                                        @csrf
                                        <select name="hasil_kelulusan" aria-label="Pilih hasil kelulusan"
                                                class="h-8 rounded-lg border border-[#E6E1EC] bg-white px-2 text-xs font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                                            <option value="lulus" {{ old('hasil_kelulusan', $p->hasil_kelulusan) === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                            <option value="tidak_lulus" {{ old('hasil_kelulusan', $p->hasil_kelulusan) === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                            <option value="belum" {{ old('hasil_kelulusan', $p->hasil_kelulusan) === 'belum' ? 'selected' : '' }}>Belum</option>
                                        </select>
                                        <input type="text" name="catatan_admin" value="{{ old('catatan', $p->catatan_admin) }}"
                                               placeholder="Catatan (opsional)"
                                               class="h-8 w-32 rounded-lg border border-[#E6E1EC] bg-white px-2.5 text-xs text-[#14141F] placeholder:text-[#818CA2] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                                        <button type="submit"
                                                class="inline-flex h-8 items-center rounded-lg bg-black px-3 text-xs font-semibold text-white transition hover:bg-[#14141F]/85">
                                            Set
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-sm text-[#4E566E]">Belum ada data pendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>{{ $pendaftaran->links() }}</div>

    {{-- Bulk set hasil --}}
    <section class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <h2 class="font-heading text-lg font-bold tracking-tight text-[#14141F]">Set Hasil Secara Bulk</h2>
        <p class="mt-1 mb-5 text-sm text-[#4E566E]">Pilih satu atau lebih pendaftar untuk ditetapkan hasil kelulusannya secara bersamaan.</p>

        <form method="POST" action="{{ route('admin.pengumuman.bulk') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-medium text-[#4E566E]">Hasil Kelulusan <span class="text-rose-500">*</span></label>
                    <select name="hasil_kelulusan"
                            class="h-10 w-full rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25" required>
                        <option value="lulus">Lulus</option>
                        <option value="tidak_lulus">Tidak Lulus</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-[#4E566E]">Catatan Admin</label>
                    <input type="text" name="catatan_admin"
                           class="h-10 w-full rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-medium text-[#4E566E]">Pilih Pendaftar <span class="text-rose-500">*</span></label>
                <div class="max-h-60 space-y-1.5 overflow-y-auto rounded-xl border border-[#E6E1EC] bg-[#FAF8FC] p-3">
                    @forelse($pendaftaran as $p)
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-transparent p-2 transition hover:border-[#BF8AFC] hover:bg-white">
                            <input type="checkbox" name="pilihan[]" value="{{ $p->id }}"
                                   class="size-4 rounded border-[#D9D0E5] text-[#BF8AFC] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                            <div>
                                <div class="text-sm font-semibold text-[#14141F]">{{ $p->user->name }}</div>
                                <div class="text-xs text-[#4E566E]">{{ $p->nomor_pendaftaran ?? '-' }} &middot; {{ $p->jalur?->kode ?? '-' }}</div>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-[#4E566E]">Tidak ada pendaftar yang cocok dengan filter saat ini.</p>
                    @endforelse
                </div>
            </div>

            <button type="submit"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-black px-6 text-sm font-semibold text-white transition hover:bg-[#14141F]/85">
                Set Hasil untuk yang Dipilih
            </button>
        </form>
    </section>
</div>

@include('admin.partials.dt2-script')
@endsection
