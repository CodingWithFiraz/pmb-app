@extends('layouts.pendaftar')
@section('title', 'Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Ringkasan tagihan --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="flex flex-wrap justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Tagihan Pembayaran</h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $pendaftaran->nomor_pendaftaran ?? 'Pendaftaran' }} &middot; {{ $pendaftaran->jalur?->nama }}
                    @if($pendaftaran->prodi)
                        &middot; {{ $pendaftaran->prodi->nama }}
                    @endif
                </p>
            </div>

            <div class="text-right text-sm">
                <div class="text-gray-500">Total tagihan</div>
                <div class="text-xl font-bold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</div>
                <div class="mt-1 text-gray-500">Sudah lunas: Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Daftar tagihan --}}
    <div class="space-y-4 mb-6">
        @forelse($tagihan as $t)
            @php
                $terkunci = $t->jenis === 'ukt'
                    && $tagihanPendaftaran
                    && $tagihanPendaftaran->status !== 'paid';
            @endphp

            <div class="bg-white shadow rounded-xl p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $t->jenisLabel() }}</h3>
                        <div class="mt-1 text-2xl font-bold text-blue-700">Rp {{ number_format($t->nominal, 0, ',', '.') }}</div>

                        @if($t->order_id)
                            <div class="mt-1 font-mono text-xs text-gray-500">Order ID: {{ $t->order_id }}</div>
                        @endif

                        @if($t->bukti_path)
                            <div class="mt-1 text-xs text-gray-500">Bukti transfer: {{ basename($t->bukti_path) }}</div>
                        @endif
                    </div>

                    <span class="tagihan-status rounded-full px-3 py-1 text-xs font-semibold {{ $t->status === 'paid' ? 'bg-green-100 text-green-700' : ($t->status === 'failed' ? 'bg-red-100 text-red-700' : ($t->status === 'expired' ? 'bg-gray-100 text-gray-600' : 'bg-amber-100 text-amber-700')) }}"
                        data-tagihan-status="{{ $t->id }}" data-status="{{ $t->status }}">
                        {{ $t->statusLabel() }}
                    </span>
                </div>

                @if($t->paid_at)
                    <p class="mt-2 text-sm text-green-700">Lunas pada {{ $t->paid_at->translatedFormat('d F Y H:i') }}</p>
                @endif

                @if($t->catatan_admin)
                    <p class="mt-3 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">{{ $t->catatan_admin }}</p>
                @endif

                @if($t->status !== 'paid')
                    @if($terkunci)
                        <p class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            Tagihan UKT dapat dibayar setelah tagihan biaya pendaftaran dilunasi.
                        </p>
                    @else
                        <form method="POST" action="{{ route('pendaftar.pembayaran.snap', $t) }}" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Bayar via Midtrans
                            </button>
                        </form>
                    @endif

                    {{-- Fallback manual: unggah bukti bayar --}}
                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-500">
                            Belum dapat memakai Midtrans? Unggah bukti transfer manual, bukti akan ditinjau panitia.
                        </p>

                        <form method="POST" action="{{ route('pendaftar.pembayaran.bukti', $t) }}"
                            enctype="multipart/form-data" class="mt-2 flex flex-wrap items-end gap-3">
                            @csrf

                            <div>
                                <label for="bukti-{{ $t->id }}" class="block text-xs font-medium text-gray-600">
                                    Bukti transfer (JPG, PNG, PDF, maks 2 MB)
                                </label>
                                <input type="file" id="bukti-{{ $t->id }}" name="bukti"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="mt-1 block text-sm text-gray-600
                                        file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-blue-700">
                                @error('bukti')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Unggah Bukti
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Belum ada tagihan pembayaran.</p>
        @endforelse
    </div>

    {{-- Riwayat pembayaran --}}
    @if($tagihan->isNotEmpty())
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="font-bold text-lg mb-3">Riwayat Pembayaran</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-3 py-2">Tagihan</th>
                            <th class="text-left px-3 py-2">Nominal</th>
                            <th class="text-left px-3 py-2">Metode</th>
                            <th class="text-left px-3 py-2">Status</th>
                            <th class="text-left px-3 py-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihan as $t)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ $t->jenisLabel() }}</td>
                                <td class="px-3 py-2">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $t->metode ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="tagihan-status rounded-full px-3 py-1 text-xs font-semibold {{ $t->status === 'paid' ? 'bg-green-100 text-green-700' : ($t->status === 'failed' ? 'bg-red-100 text-red-700' : ($t->status === 'expired' ? 'bg-gray-100 text-gray-600' : 'bg-amber-100 text-amber-700')) }}"
                                        data-tagihan-status="{{ $t->id }}" data-status="{{ $t->status }}">
                                        {{ $t->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-gray-600">
                                    {{ $t->paid_at?->translatedFormat('d M Y H:i') ?? $t->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
    (function () {
        const endpoint = @json(route('pendaftar.pembayaran.status'));

        const labels = {
            pending: 'Menunggu Pembayaran',
            paid: 'Lunas',
            expired: 'Kedaluwarsa',
            failed: 'Gagal',
        };

        const classes = {
            pending: 'bg-amber-100 text-amber-700',
            paid: 'bg-green-100 text-green-700',
            expired: 'bg-gray-100 text-gray-600',
            failed: 'bg-red-100 text-red-700',
        };

        async function refresh() {
            try {
                const response = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });

                if (! response.ok) {
                    return;
                }

                const data = await response.json();

                (data.tagihan || []).forEach(function (item) {
                    document.querySelectorAll('[data-tagihan-status="' + item.id + '"]').forEach(function (el) {
                        if (el.dataset.status === item.status) {
                            return;
                        }

                        el.dataset.status = item.status;
                        el.textContent = labels[item.status] || item.status;
                        el.className = 'tagihan-status rounded-full px-3 py-1 text-xs font-semibold '
                            + (classes[item.status] || '');
                    });
                });
            } catch (error) {
                // Abaikan error polling, akan dicoba lagi pada siklus berikutnya.
            }
        }

        window.setInterval(refresh, 10000);
    })();
</script>
@endsection
