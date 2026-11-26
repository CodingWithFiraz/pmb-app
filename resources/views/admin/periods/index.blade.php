@extends('layouts.admin')
@section('title', 'Periode PMB')

@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6">
    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] sm:text-3xl">
                Periode PMB
            </h1>
            <p class="mt-1 text-sm text-[#4E566E] sm:text-base">
                Atur periode penerimaan mahasiswa baru, rentang tanggal pendaftaran, dan status aktif.
            </p>
        </div>

        <a href="{{ route('admin.periode.create') }}"
           class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-black px-4 text-sm font-semibold text-white transition duration-150 hover:-translate-y-0.5 hover:bg-[#14141F]/85 active:translate-y-0">
            + Tambah Periode
        </a>
    </div>

    {{-- DataTable 10 (Watermelon UI): Sortable header + checkbox + toolbar pagination terpisah --}}
    <div class="space-y-4">
        <div class="overflow-hidden rounded-2xl border border-[#E6E1EC] bg-white">
            <div class="overflow-x-auto">
                <table id="dt10-table" class="w-full text-left text-sm">
                    <thead class="border-b border-[#EFEBF4] bg-[#FAF8FC] text-xs font-semibold text-[#4E566E]">
                        <tr>
                            <th class="h-12 w-11 pl-5 pr-2">
                                <input id="dt10-select-all" type="checkbox" aria-label="Pilih semua periode"
                                       class="size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30">
                            </th>
                            <th class="h-12 px-3">
                                <button type="button" data-sort-col="nama"
                                        class="dt10-sort inline-flex items-center gap-1.5 font-semibold text-[#4E566E] hover:text-[#14141F]">
                                    <span>Nama Periode</span>
                                    <span class="dt10-sort-icon text-[#818CA2]" aria-hidden="true">&#8597;</span>
                                </button>
                            </th>
                            <th class="h-12 px-3">
                                <button type="button" data-sort-col="tahun"
                                        class="dt10-sort inline-flex items-center gap-1.5 font-semibold text-[#4E566E] hover:text-[#14141F]">
                                    <span>Tahun Akademik</span>
                                    <span class="dt10-sort-icon text-[#818CA2]" aria-hidden="true">&#8597;</span>
                                </button>
                            </th>
                            <th class="h-12 px-3">
                                <button type="button" data-sort-col="mulai"
                                        class="dt10-sort inline-flex items-center gap-1.5 font-semibold text-[#4E566E] hover:text-[#14141F]">
                                    <span>Jadwal</span>
                                    <span class="dt10-sort-icon text-[#818CA2]" aria-hidden="true">&#8597;</span>
                                </button>
                            </th>
                            <th class="h-12 px-3">
                                <button type="button" data-sort-col="status"
                                        class="dt10-sort inline-flex items-center gap-1.5 font-semibold text-[#4E566E] hover:text-[#14141F]">
                                    <span>Status</span>
                                    <span class="dt10-sort-icon text-[#818CA2]" aria-hidden="true">&#8597;</span>
                                </button>
                            </th>
                            <th class="h-12 pl-3 pr-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dt10-tbody" class="divide-y divide-[#EFEBF4]">
                        @forelse($periods as $period)
                            <tr class="dt10-row transition-colors hover:bg-[#FAF8FC] data-[state=selected]:bg-[#F5EFFB]"
                                data-nama="{{ mb_strtolower($period->nama) }}"
                                data-tahun="{{ $period->tahun_akademik }}"
                                data-mulai="{{ $period->tanggal_mulai->format('Y-m-d') }}"
                                data-status="{{ $period->is_active ? '1' : '0' }}">
                                <td class="py-3.5 pl-5 pr-2 align-middle">
                                    <input type="checkbox" class="dt10-row-check size-4 rounded border-[#D9D0E5] text-[#7E22CE] accent-[#BF8AFC] focus:ring-[#BF8AFC]/30"
                                           aria-label="Pilih {{ $period->nama }}">
                                </td>
                                <td class="px-3 py-3.5 align-middle">
                                    <div class="font-semibold text-[#14141F]">{{ $period->nama }}</div>
                                    <div class="mt-0.5 text-xs text-[#4E566E] tabular-nums">{{ $period->pendaftaran_count }} pendaftar</div>
                                </td>
                                <td class="px-3 py-3.5 align-middle font-numeric font-medium text-[#14141F] tabular-nums">
                                    {{ $period->tahun_akademik }}
                                </td>
                                <td class="px-3 py-3.5 align-middle text-[#4E566E] tabular-nums">
                                    {{ $period->tanggal_mulai->format('d/m/Y') }} &ndash; {{ $period->tanggal_selesai->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-3.5 align-middle">
                                    <span class="inline-flex items-center rounded-full border-none px-2.5 py-1 text-xs font-semibold {{ $period->is_active ? 'bg-emerald-600/10 text-emerald-700' : 'bg-slate-500/10 text-slate-600' }}">
                                        {{ $period->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 pl-3 pr-5 text-right align-middle">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.periode.edit', $period) }}"
                                           class="inline-flex items-center rounded-lg border border-[#E6E1EC] bg-white px-2.5 py-1 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] hover:text-[#7E22CE]">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.periode.toggle', $period) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-lg border border-[#E6E1EC] bg-[#FAF8FC] px-2.5 py-1 text-xs font-semibold text-[#7E22CE] transition hover:border-[#BF8AFC] hover:bg-[#EFE5FA]">
                                                {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.periode.destroy', $period) }}"
                                              onsubmit="return confirm('Hapus periode ini?')">
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
                                <td colspan="6" class="h-24 text-center text-sm text-[#4E566E]">Belum ada periode.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Toolbar pagination terpisah ala DataTable 10 --}}
        <div class="flex flex-col gap-4 rounded-xl border border-[#E6E1EC] bg-white px-4 py-3 shadow-xs lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center justify-center gap-3 lg:justify-start">
                <label for="dt10-page-size" class="text-sm font-medium text-[#4E566E]">Baris per halaman</label>
                <select id="dt10-page-size"
                        class="h-9 rounded-lg border border-[#E6E1EC] bg-white px-3 text-sm font-medium text-[#14141F] focus:border-[#BF8AFC] focus:outline-none focus:ring-2 focus:ring-[#BF8AFC]/25">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            <div class="flex justify-center text-sm text-[#4E566E] whitespace-nowrap lg:flex-1 lg:justify-end">
                <p id="dt10-range" aria-live="polite" class="tabular-nums"></p>
            </div>

            <div class="flex items-center justify-center gap-1.5 lg:justify-end">
                <button id="dt10-first" type="button" aria-label="Halaman pertama"
                        class="inline-flex size-9 items-center justify-center rounded-lg border border-[#E6E1EC] bg-white text-[#14141F] transition hover:bg-[#FAF8FC] disabled:pointer-events-none disabled:opacity-40">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                    </svg>
                </button>
                <button id="dt10-prev" type="button" aria-label="Halaman sebelumnya"
                        class="inline-flex size-9 items-center justify-center rounded-lg border border-[#E6E1EC] bg-white text-[#14141F] transition hover:bg-[#FAF8FC] disabled:pointer-events-none disabled:opacity-40">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button id="dt10-next" type="button" aria-label="Halaman berikutnya"
                        class="inline-flex size-9 items-center justify-center rounded-lg border border-[#E6E1EC] bg-white text-[#14141F] transition hover:bg-[#FAF8FC] disabled:pointer-events-none disabled:opacity-40">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <button id="dt10-last" type="button" aria-label="Halaman terakhir"
                        class="inline-flex size-9 items-center justify-center rounded-lg border border-[#E6E1EC] bg-white text-[#14141F] transition hover:bg-[#FAF8FC] disabled:pointer-events-none disabled:opacity-40">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @if($periods->hasPages())
        <div>{{ $periods->links() }}</div>
    @endif
</div>

<script>
    (function () {
        const tbody     = document.getElementById('dt10-tbody');
        const rows      = Array.from(document.querySelectorAll('.dt10-row'));
        const selectAll = document.getElementById('dt10-select-all');
        const sizeEl    = document.getElementById('dt10-page-size');
        const rangeEl   = document.getElementById('dt10-range');
        const firstBtn  = document.getElementById('dt10-first');
        const prevBtn   = document.getElementById('dt10-prev');
        const nextBtn   = document.getElementById('dt10-next');
        const lastBtn   = document.getElementById('dt10-last');
        const sortBtns  = Array.from(document.querySelectorAll('.dt10-sort'));

        let pageIndex = 0;
        let pageSize  = Number(sizeEl?.value || 10);
        let sortCol   = null;
        let sortDir   = 'asc';

        function visibleRows() {
            const start = pageIndex * pageSize;
            return rows.slice(start, start + pageSize);
        }

        function syncSelection() {
            const visible = visibleRows();
            const checks  = visible.map((r) => r.querySelector('.dt10-row-check')).filter(Boolean);
            const checked = checks.filter((c) => c.checked).length;

            rows.forEach((r) => {
                const cb = r.querySelector('.dt10-row-check');
                if (cb?.checked) r.setAttribute('data-state', 'selected');
                else r.removeAttribute('data-state');
            });

            if (selectAll) {
                selectAll.checked = checks.length > 0 && checked === checks.length;
                selectAll.indeterminate = checked > 0 && checked < checks.length;
            }
        }

        function render() {
            const total = rows.length;
            const pageCount = Math.max(1, Math.ceil(total / pageSize));
            if (pageIndex >= pageCount) pageIndex = pageCount - 1;
            const start = pageIndex * pageSize;
            const end   = start + pageSize;

            rows.forEach((row, idx) => {
                row.classList.toggle('hidden', idx < start || idx >= end);
            });

            if (rangeEl) {
                const from = total === 0 ? 0 : start + 1;
                const to   = Math.min(end, total);
                rangeEl.innerHTML = `<span class="font-semibold text-[#14141F]">${from}-${to}</span> dari <span class="font-semibold text-[#14141F]">${total}</span>`;
            }

            if (firstBtn) firstBtn.disabled = pageIndex === 0;
            if (prevBtn)  prevBtn.disabled  = pageIndex === 0;
            if (nextBtn)  nextBtn.disabled  = pageIndex >= pageCount - 1;
            if (lastBtn)  lastBtn.disabled  = pageIndex >= pageCount - 1;

            syncSelection();
        }

        sortBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const col = btn.getAttribute('data-sort-col');
                if (sortCol === col) {
                    sortDir = sortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    sortCol = col;
                    sortDir = 'asc';
                }

                rows.sort((a, b) => {
                    const av = a.dataset[col] || '';
                    const bv = b.dataset[col] || '';
                    const cmp = av.localeCompare(bv, 'id', { numeric: true });
                    return sortDir === 'asc' ? cmp : -cmp;
                });

                rows.forEach((r) => tbody?.appendChild(r));

                sortBtns.forEach((b) => {
                    const icon = b.querySelector('.dt10-sort-icon');
                    if (!icon) return;
                    if (b.getAttribute('data-sort-col') === sortCol) {
                        icon.textContent = sortDir === 'asc' ? '↑' : '↓';
                        icon.classList.add('text-[#7E22CE]');
                    } else {
                        icon.textContent = '↕';
                        icon.classList.remove('text-[#7E22CE]');
                    }
                });

                pageIndex = 0;
                render();
            });
        });

        selectAll?.addEventListener('change', function () {
            visibleRows().forEach((r) => {
                const cb = r.querySelector('.dt10-row-check');
                if (cb) cb.checked = selectAll.checked;
            });
            syncSelection();
        });

        rows.forEach((r) => {
            r.querySelector('.dt10-row-check')?.addEventListener('change', syncSelection);
        });

        sizeEl?.addEventListener('change', function () {
            pageSize  = Number(sizeEl.value);
            pageIndex = 0;
            render();
        });

        firstBtn?.addEventListener('click', () => { pageIndex = 0; render(); });
        prevBtn?.addEventListener('click',  () => { pageIndex = Math.max(0, pageIndex - 1); render(); });
        nextBtn?.addEventListener('click',  () => { pageIndex++; render(); });
        lastBtn?.addEventListener('click',  () => { pageIndex = Math.max(0, Math.ceil(rows.length / pageSize) - 1); render(); });

        render();
    })();
</script>
@endsection
