@extends('layouts.admin')
@section('title', 'Tarif UKT')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6">
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">Matrix Tarif UKT</h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">Nominal UKT per jalur masuk, program studi, dan golongan.</p>
        </div>
    </div>

    {{-- Filter periode --}}
    <form method="GET" class="flex flex-wrap items-end gap-3 rounded-2xl border border-[#E6E1EC] bg-white p-4">
        <div>
            <label for="tarif-periode" class="mb-1 block text-xs font-medium text-[#4E566E]">Periode</label>
            <select id="tarif-periode" name="periode_id"
                    class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                @foreach($periodeList as $p)
                    <option value="{{ $p->id }}" {{ $periode && $periode->id === $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="inline-flex h-9 items-center justify-center rounded-lg bg-black px-4 text-sm font-semibold text-white transition hover:bg-[#14141F]/85">
            Tampilkan
        </button>
    </form>

    @if($periode)
        <form method="POST" action="{{ route('admin.tarif-ukt.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="periode_id" value="{{ $periode->id }}">

            @forelse($jalur as $j)
                {{-- DataTable 2 (sama dengan admin/fakultas): Density selector + tabel bersih ber-checkbox --}}
                <div class="space-y-3" data-dt2-root>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <h2 class="font-heading text-lg font-bold tracking-tight text-[#14141F]">{{ $j->nama }}</h2>
                            <span class="inline-flex items-center rounded-md bg-[#F5EFFB] px-2.5 py-0.5 font-mono text-xs font-bold text-[#7E22CE]">
                                {{ $j->kode }}
                            </span>
                        </div>

                        <div class="inline-flex items-center gap-2.5">
                            <label for="dt2-density-tarif-{{ $j->id }}" class="text-xs font-medium text-[#4E566E]">Kerapatan</label>
                            <select id="dt2-density-tarif-{{ $j->id }}" data-dt2-density aria-label="Pilih kerapatan tabel {{ $j->nama }}"
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
                                            <input type="checkbox" data-dt2-select-all aria-label="Pilih semua prodi jalur {{ $j->nama }}"
                                                   class="size-4 rounded border-[#D9D0E5] text-[#BF8AFC] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                                        </th>
                                        <th class="px-4">Program Studi</th>
                                        @foreach($golongan as $g)
                                            <th class="px-3 text-right last:pr-5">{{ $g->kode }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#EFEBF4]">
                                    @forelse($prodi as $p)
                                        <tr class="transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]">
                                            <td class="pl-5 pr-2">
                                                <input type="checkbox" data-dt2-row-check aria-label="Pilih {{ $p->nama }}"
                                                       class="size-4 rounded border-[#D9D0E5] text-[#BF8AFC] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                                            </td>
                                            <td class="px-4">
                                                <div class="font-semibold text-[#14141F]">{{ $p->nama }}</div>
                                                <div class="mt-0.5 font-mono text-xs text-[#4E566E]">{{ $p->kode }}</div>
                                            </td>

                                            @foreach($golongan as $g)
                                                @php
                                                    $key = $j->id.'-'.$p->id.'-'.$g->id;
                                                    $nominal = $tarif[$key]->nominal ?? null;
                                                @endphp
                                                <td class="px-3 last:pr-5">
                                                    <input type="number" name="tarif[{{ $key }}]" min="0" step="1000"
                                                        value="{{ old('tarif.'.$key, $nominal) }}"
                                                        class="w-full min-w-28 rounded-lg border border-[#E6E1EC] bg-white px-2.5 py-1.5 text-right font-numeric text-sm text-[#14141F] tabular-nums focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $golongan->count() + 2 }}" class="py-10 text-center text-sm text-[#4E566E]">Belum ada program studi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-[#E6E1EC] bg-white p-8 text-center text-sm text-[#4E566E]">
                    Belum ada jalur masuk.
                </div>
            @endforelse

            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-black px-6 text-sm font-semibold text-white transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
                    Simpan Tarif UKT
                </button>
            </div>
        </form>
    @else
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-8 text-center text-sm text-[#4E566E]">
            Belum ada periode pendaftaran.
        </div>
    @endif
</div>

@include('admin.partials.dt2-script')
@endsection
