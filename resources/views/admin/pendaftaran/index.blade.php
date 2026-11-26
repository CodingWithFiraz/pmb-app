@extends('layouts.admin')
@section('title', 'Daftar Pendaftar')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6">
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">
                Daftar Pendaftar
            </h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">
                Kelola, saring, dan periksa berkas calon mahasiswa baru berdasarkan periode, jalur, dan status.
            </p>
        </div>

        <a href="{{ route('admin.pendaftaran.export', request()->query()) }}"
           class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-xl border border-[#E6E1EC] bg-white px-4 text-sm font-semibold text-[#14141F] transition duration-150 hover:-translate-y-0.5 hover:border-[#BF8AFC] hover:bg-[#FAF8FC] active:translate-y-0">
            <svg class="size-4 text-[#4E566E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export CSV
        </a>
    </div>

    {{-- DataTable 4 (Watermelon UI): Filter terintegrasi + header dashed + checkbox + avatar + badge lembut --}}
    <section class="overflow-hidden rounded-2xl border border-[#E6E1EC] bg-white">
        {{-- Filter bar (border-b dashed ala DataTable 4) --}}
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="border-b border-dashed border-[#E6E1EC] p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
                {{-- Pencarian --}}
                <div class="space-y-1.5 sm:col-span-2 lg:col-span-4">
                    <label for="dt4-search" class="block text-xs font-medium text-[#4E566E]">Pendaftar</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-[#4E566E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input id="dt4-search" type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama / nomor / email..."
                               class="h-10 w-full rounded-lg border border-[#E6E1EC] bg-white pl-9 pr-3 text-sm text-[#14141F] placeholder:text-[#818CA2] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                    </div>
                </div>

                {{-- Filter Periode --}}
                <div class="space-y-1.5 lg:col-span-2">
                    <label for="dt4-periode" class="block text-xs font-medium text-[#4E566E]">Periode</label>
                    <select id="dt4-periode" name="periode_id"
                            class="h-10 w-full rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                        <option value="">Semua Periode</option>
                        @foreach($periode as $pr)
                            <option value="{{ $pr->id }}" {{ (int) request('periode_id') === $pr->id ? 'selected' : '' }}>
                                {{ $pr->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jalur --}}
                <div class="space-y-1.5 lg:col-span-2">
                    <label for="dt4-jalur" class="block text-xs font-medium text-[#4E566E]">Jalur Masuk</label>
                    <select id="dt4-jalur" name="jalur_id"
                            class="h-10 w-full rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                        <option value="">Semua Jalur</option>
                        @foreach($jalur as $j)
                            <option value="{{ $j->id }}" {{ (int) request('jalur_id') === $j->id ? 'selected' : '' }}>
                                {{ $j->kode }} - {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="space-y-1.5 lg:col-span-2">
                    <label for="dt4-status" class="block text-xs font-medium text-[#4E566E]">Status</label>
                    <select id="dt4-status" name="status"
                            class="h-10 w-full rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                        <option value="">Semua Status</option>
                        @foreach([
                            'draft'               => 'Draft',
                            'menunggu_verifikasi' => 'Menunggu Verifikasi',
                            'terverifikasi'       => 'Terverifikasi',
                            'ditolak'             => 'Ditolak',
                        ] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Filter & Reset --}}
                <div class="flex items-center gap-2 lg:col-span-2">
                    <button type="submit"
                            class="inline-flex h-10 flex-1 items-center justify-center rounded-lg bg-black px-4 text-sm font-semibold text-white transition hover:bg-[#14141F]/85">
                        Filter
                    </button>
                    <a href="{{ route('admin.pendaftaran.index') }}"
                       class="inline-flex h-10 items-center justify-center rounded-lg border border-[#E6E1EC] bg-white px-3.5 text-sm font-medium text-[#4E566E] transition hover:bg-[#FAF8FC] hover:text-[#14141F]">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Tabel DataTable 4 --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="h-12 w-11 border-b border-dashed border-[#E6E1EC] bg-transparent pl-5 pr-2 font-medium">
                            <input id="dt4-select-all" type="checkbox" aria-label="Pilih semua pendaftar"
                                   class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            No.
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            Pendaftar
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            Program Studi
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            Jalur
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            Periode
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent px-3 text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            Status
                        </th>
                        <th class="h-12 border-b border-dashed border-[#E6E1EC] bg-transparent pl-3 pr-5 text-right text-[11px] font-medium uppercase tracking-[0.14em] text-[#4E566E]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFEBF4]">
                    @forelse($pendaftaran as $idx => $p)
                        @php
                            $rowNumber = method_exists($pendaftaran, 'firstItem') && $pendaftaran->firstItem()
                                ? $pendaftaran->firstItem() + $idx
                                : $idx + 1;
                            $initials = collect(explode(' ', trim($p->user->name ?? 'P')))
                                ->filter()
                                ->take(2)
                                ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                ->implode('') ?: 'P';
                            $badgeClass = match($p->status) {
                                'terverifikasi'       => 'bg-emerald-600/10 text-emerald-700',
                                'ditolak'             => 'bg-rose-600/10 text-rose-700',
                                'menunggu_verifikasi' => 'bg-amber-600/10 text-amber-700',
                                default               => 'bg-slate-500/10 text-slate-600',
                            };
                        @endphp
                        <tr class="dt4-row transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]">
                            <td class="py-3.5 pl-5 pr-2 align-middle">
                                <input type="checkbox" class="dt4-row-check size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30"
                                       aria-label="Pilih {{ $p->user->name }}">
                            </td>
                            <td class="px-3 py-3.5 align-middle font-numeric text-sm text-[#4E566E] tabular-nums">
                                {{ $rowNumber }}
                            </td>
                            <td class="px-3 py-3.5 align-middle">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-md bg-[#F5EFFB] font-heading text-xs font-bold text-[#7E22CE]" aria-hidden="true">
                                        {{ $initials }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-[#14141F]">{{ $p->user->name }}</div>
                                        <div class="font-mono text-xs text-[#4E566E]">{{ $p->nomor_pendaftaran ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3.5 align-middle font-medium text-[#14141F]">
                                {{ $p->prodi->nama }}
                            </td>
                            <td class="px-3 py-3.5 align-middle">
                                <span class="inline-flex items-center rounded-md bg-[#F8F6F9] px-2.5 py-0.5 text-xs font-semibold text-[#7E22CE]">
                                    {{ $p->jalur->kode }}
                                </span>
                            </td>
                            <td class="px-3 py-3.5 align-middle text-[#4E566E] tabular-nums">
                                {{ $p->periode->tahun_akademik }}
                            </td>
                            <td class="px-3 py-3.5 align-middle">
                                <span class="inline-flex items-center rounded-full border-none px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ $p->statusLabel() }}
                                </span>
                            </td>
                            <td class="py-3.5 pl-3 pr-5 text-right align-middle">
                                <a href="{{ route('admin.pendaftaran.show', $p) }}"
                                   class="inline-flex items-center gap-1 rounded-lg border border-[#E6E1EC] bg-white px-3 py-1.5 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:text-[#7E22CE]">
                                    Detail <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="h-28 text-center text-sm text-[#4E566E]">
                                Tidak ada data pendaftar yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: ringkasan pilihan & pagination --}}
        <div class="flex flex-col gap-3 border-t border-dashed border-[#E6E1EC] px-5 py-4 text-xs text-[#4E566E] sm:flex-row sm:items-center sm:justify-between">
            <div id="dt4-selection-status">
                Menampilkan <strong class="font-semibold text-[#14141F]">{{ $pendaftaran->count() }}</strong>
                @if(method_exists($pendaftaran, 'total'))
                    dari <strong class="font-semibold text-[#14141F]">{{ $pendaftaran->total() }}</strong> pendaftar
                @else
                    pendaftar
                @endif
            </div>
            <div>
                {{ $pendaftaran->links() }}
            </div>
        </div>
    </section>
</div>

<script>
    (function () {
        const selectAll = document.getElementById('dt4-select-all');
        const checks    = Array.from(document.querySelectorAll('.dt4-row-check'));
        const statusEl  = document.getElementById('dt4-selection-status');
        const defaultStatusHtml = statusEl ? statusEl.innerHTML : '';

        function syncSelection() {
            const selectedCount = checks.filter((cb) => cb.checked).length;
            checks.forEach((cb) => {
                const row = cb.closest('.dt4-row');
                if (row) {
                    if (cb.checked) {
                        row.setAttribute('data-state', 'selected');
                    } else {
                        row.removeAttribute('data-state');
                    }
                }
            });

            if (selectAll) {
                selectAll.checked = checks.length > 0 && selectedCount === checks.length;
                selectAll.indeterminate = selectedCount > 0 && selectedCount < checks.length;
            }

            if (statusEl) {
                statusEl.innerHTML = selectedCount > 0
                    ? `<strong class="font-semibold text-[#7E22CE]">${selectedCount}</strong> dari <strong class="font-semibold text-[#14141F]">${checks.length}</strong> baris dipilih`
                    : defaultStatusHtml;
            }
        }

        selectAll?.addEventListener('change', function () {
            checks.forEach((cb) => { cb.checked = selectAll.checked; });
            syncSelection();
        });

        checks.forEach((cb) => cb.addEventListener('change', syncSelection));
    })();
</script>
@endsection
