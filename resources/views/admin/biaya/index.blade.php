@extends('layouts.admin')
@section('title', 'Biaya Pendaftaran')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6" data-dt2-root>
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">Biaya Pendaftaran</h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">Kelola nominal biaya pendaftaran untuk setiap jalur masuk pada masing-masing periode.</p>
        </div>
        <a href="{{ route('admin.biaya-pendaftaran.create') }}"
           class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-black px-4 text-sm font-semibold text-white transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
            + Tambah Biaya
        </a>
    </div>

    {{-- DataTable 2 (Watermelon UI): Density selector + filter periode + tabel bersih ber-checkbox --}}
    <div class="space-y-3">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="inline-flex items-center gap-2.5">
                <label for="dt2-density-biaya" class="text-xs font-medium text-[#4E566E]">Kerapatan</label>
                <select id="dt2-density-biaya" data-dt2-density aria-label="Pilih kerapatan tabel"
                        class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                    <option value="compact">Compact</option>
                    <option value="standard" selected>Standard</option>
                    <option value="flexible">Flexible</option>
                </select>
            </div>

            <form method="GET" class="flex flex-wrap items-center gap-2">
                <label for="biaya-periode-filter" class="sr-only">Filter Periode</label>
                <select id="biaya-periode-filter" name="periode_id"
                        class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                    <option value="">Semua Periode</option>
                    @foreach($periodeList as $p)
                        <option value="{{ $p->id }}" {{ (int) request('periode_id') === $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="inline-flex h-9 items-center justify-center rounded-lg bg-black px-3.5 text-sm font-semibold text-white transition hover:bg-[#14141F]/85">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#E6E1EC] bg-white">
            <div class="overflow-x-auto">
                <table data-dt2-table class="w-full text-left text-sm [&_td]:py-3 [&_th]:py-2.5">
                    <thead class="border-b border-[#EFEBF4] bg-[#FAF8FC] text-xs font-semibold text-[#4E566E]">
                        <tr>
                            <th class="w-11 pl-5 pr-2">
                                <input type="checkbox" data-dt2-select-all aria-label="Pilih semua biaya pendaftaran"
                                       class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                            </th>
                            <th class="px-4">Periode</th>
                            <th class="px-4">Jalur Masuk</th>
                            <th class="px-4 text-right">Nominal</th>
                            <th class="pl-4 pr-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFEBF4]">
                        @forelse($biaya as $b)
                            <tr class="transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]">
                                <td class="pl-5 pr-2">
                                    <input type="checkbox" data-dt2-row-check aria-label="Pilih {{ $b->jalur->nama }}"
                                           class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                                </td>
                                <td class="px-4 text-[#4E566E]">{{ $b->periode->nama }}</td>
                                <td class="px-4 font-semibold text-[#14141F]">{{ $b->jalur->nama }}</td>
                                <td class="px-4 text-right font-numeric tabular-nums">
                                    @if($b->nominal === null)
                                        <span class="inline-flex items-center rounded-full bg-emerald-600/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Gratis</span>
                                    @else
                                        <span class="font-bold text-[#14141F]">Rp {{ number_format($b->nominal, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="pl-4 pr-5 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.biaya-pendaftaran.edit', $b) }}"
                                           class="inline-flex items-center rounded-lg border border-[#E6E1EC] bg-white px-2.5 py-1 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:text-[#7E22CE]">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.biaya-pendaftaran.destroy', $b) }}"
                                              onsubmit="return confirm('Hapus biaya pendaftaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-[#4E566E]">Belum ada data biaya pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>{{ $biaya->links() }}</div>
</div>

@include('admin.partials.dt2-script')
@endsection
