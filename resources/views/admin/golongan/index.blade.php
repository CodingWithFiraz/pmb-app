@extends('layouts.admin')
@section('title', 'Golongan UKT')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6" data-dt2-root>
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">Golongan UKT</h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">Kelola kategori golongan Uang Kuliah Tunggal beserta deskripsi kriterianya.</p>
        </div>
        <a href="{{ route('admin.golongan-ukt.create') }}"
           class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-black px-4 text-sm font-semibold text-white transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
            + Tambah Golongan
        </a>
    </div>

    {{-- DataTable 2 (Watermelon UI) --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between gap-3">
            <div class="inline-flex items-center gap-2.5">
                <label for="dt2-density-golongan" class="text-xs font-medium text-[#4E566E]">Kerapatan</label>
                <select id="dt2-density-golongan" data-dt2-density aria-label="Pilih kerapatan tabel"
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
                                <input type="checkbox" data-dt2-select-all aria-label="Pilih semua golongan UKT"
                                       class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                            </th>
                            <th class="px-4">Kode</th>
                            <th class="px-4">Nama Golongan</th>
                            <th class="px-4">Deskripsi</th>
                            <th class="pl-4 pr-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFEBF4]">
                        @forelse($golongan as $g)
                            <tr class="transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]">
                                <td class="pl-5 pr-2">
                                    <input type="checkbox" data-dt2-row-check aria-label="Pilih {{ $g->nama }}"
                                           class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                                </td>
                                <td class="px-4">
                                    <span class="inline-flex items-center rounded-md bg-[#F8F6F9] px-2.5 py-0.5 font-mono text-xs font-bold text-[#7E22CE]">
                                        {{ $g->kode }}
                                    </span>
                                </td>
                                <td class="px-4 font-semibold text-[#14141F]">{{ $g->nama }}</td>
                                <td class="px-4 text-[#4E566E]">{{ $g->deskripsi ?? '-' }}</td>
                                <td class="pl-4 pr-5 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.golongan-ukt.edit', $g) }}"
                                           class="inline-flex items-center rounded-lg border border-[#E6E1EC] bg-white px-2.5 py-1 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:text-[#7E22CE]">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.golongan-ukt.destroy', $g) }}"
                                              onsubmit="return confirm('Hapus golongan UKT ini?')">
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
                                <td colspan="5" class="py-10 text-center text-sm text-[#4E566E]">Belum ada golongan UKT.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>{{ $golongan->links() }}</div>
</div>

@include('admin.partials.dt2-script')
@endsection
