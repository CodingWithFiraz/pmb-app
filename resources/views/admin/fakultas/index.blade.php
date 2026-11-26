@extends('layouts.admin')
@section('title', 'Fakultas')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6" data-dt2-root>
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">Fakultas</h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">Kelola daftar fakultas beserta jumlah program studi yang terdaftar.</p>
        </div>
        <a href="{{ route('admin.fakultas.create') }}"
           class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-black px-4 text-sm font-semibold text-white transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
            + Tambah Fakultas
        </a>
    </div>

    {{-- DataTable 2 (Watermelon UI): Density selector + tabel bersih ber-checkbox --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between gap-3">
            <div class="inline-flex items-center gap-2.5">
                <label for="dt2-density-fakultas" class="text-xs font-medium text-[#4E566E]">Kerapatan</label>
                <select id="dt2-density-fakultas" data-dt2-density aria-label="Pilih kerapatan tabel"
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
                                <input type="checkbox" data-dt2-select-all aria-label="Pilih semua fakultas"
                                       class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                            </th>
                            <th class="px-4">Kode</th>
                            <th class="px-4">Nama Fakultas</th>
                            <th class="px-4">Program Studi</th>
                            <th class="px-4">Slug</th>
                            <th class="pl-4 pr-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFEBF4]">
                        @forelse($fakultas as $f)
                            <tr class="transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]">
                                <td class="pl-5 pr-2">
                                    <input type="checkbox" data-dt2-row-check aria-label="Pilih {{ $f->nama }}"
                                           class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                                </td>
                                <td class="px-4">
                                    <span class="inline-flex items-center rounded-md bg-[#F8F6F9] px-2.5 py-0.5 font-mono text-xs font-bold text-[#7E22CE]">
                                        {{ $f->kode }}
                                    </span>
                                </td>
                                <td class="px-4 font-semibold text-[#14141F]">{{ $f->nama }}</td>
                                <td class="px-4 font-numeric text-[#4E566E] tabular-nums">{{ $f->prodi_count }} prodi</td>
                                <td class="px-4 font-mono text-xs text-[#4E566E]">{{ $f->slug }}</td>
                                <td class="pl-4 pr-5 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.fakultas.edit', $f) }}"
                                           class="inline-flex items-center rounded-lg border border-[#E6E1EC] bg-white px-2.5 py-1 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:text-[#7E22CE]">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.fakultas.destroy', $f) }}"
                                              onsubmit="return confirm('Hapus fakultas ini?')">
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
                                <td colspan="6" class="py-10 text-center text-sm text-[#4E566E]">Belum ada fakultas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>{{ $fakultas->links() }}</div>
</div>

@include('admin.partials.dt2-script')
@endsection
