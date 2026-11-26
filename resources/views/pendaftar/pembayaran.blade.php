@extends('layouts.pendaftar')
@section('title', 'Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Ringkasan tagihan --}}
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6 mb-6">
        <div class="flex flex-wrap justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#14141F]">Tagihan Pembayaran</h2>
                <p class="mt-1 text-sm text-[#4E566E]">
                    {{ $pendaftaran->nomor_pendaftaran ?? 'Pendaftaran' }} &middot; {{ $pendaftaran->jalur?->nama }}
                    @if($pendaftaran->prodi)
                        &middot; {{ $pendaftaran->prodi->nama }}
                    @endif
                </p>
            </div>

            <div class="text-right text-sm">
                <div class="text-[#4E566E]">Total tagihan</div>
                <div class="text-xl font-bold text-[#14141F]">Rp {{ number_format($total, 0, ',', '.') }}</div>
                <div class="mt-1 text-[#4E566E]">Sudah lunas: Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
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

            <div class="rounded-2xl border border-[#E6E1EC] bg-white p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-[#14141F]">{{ $t->jenisLabel() }}</h3>
                        <div class="mt-1 text-2xl font-bold text-[#7E22CE]">Rp {{ number_format($t->nominal, 0, ',', '.') }}</div>

                        @if($t->order_id)
                            <div class="mt-1 font-mono text-xs text-[#4E566E]">Order ID: {{ $t->order_id }}</div>
                        @endif

                        @if($t->bukti_path)
                            <div class="mt-1 text-xs text-[#4E566E]">Bukti transfer: {{ basename($t->bukti_path) }}</div>
                        @endif
                    </div>

                    <span class="tagihan-status rounded-full px-3 py-1 text-xs font-semibold {{ $t->status === 'paid' ? 'bg-green-100 text-green-700' : ($t->status === 'failed' ? 'bg-red-100 text-red-700' : ($t->status === 'expired' ? 'bg-gray-100 text-[#4E566E]' : 'bg-amber-100 text-amber-700')) }}"
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
                        <form method="POST" action="{{ route('pendaftar.pembayaran.snap', $t) }}" class="mt-4 space-y-3" id="form-doku-{{ $t->id }}">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-[#4E566E] mb-2">
                                    Pilih Metode Pembayaran DOKU
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                    {{-- Aktif: Virtual Account --}}
                                    <label class="relative flex cursor-pointer items-center justify-between gap-2 rounded-xl border border-[#E6E1EC] bg-[#FAF8FC] px-3.5 py-2.5 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] has-[:checked]:border-[#7E22CE] has-[:checked]:bg-[#F5EFFB] has-[:checked]:text-[#7E22CE]">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="payment_method" value="virtual_account" class="accent-[#7E22CE]" checked>
                                            <span>Virtual Account (Bank)</span>
                                        </span>
                                        <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Aktif</span>
                                    </label>

                                    {{-- Aktif: Convenience Store (Alfamart / Indomaret) --}}
                                    <label class="relative flex cursor-pointer items-center justify-between gap-2 rounded-xl border border-[#E6E1EC] bg-[#FAF8FC] px-3.5 py-2.5 text-xs font-semibold text-[#14141F] transition hover:border-[#BF8AFC] has-[:checked]:border-[#7E22CE] has-[:checked]:bg-[#F5EFFB] has-[:checked]:text-[#7E22CE]">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="payment_method" value="convenience_store" class="accent-[#7E22CE]">
                                            <span>Alfamart / Indomaret</span>
                                        </span>
                                        <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Aktif</span>
                                    </label>

                                    {{-- Coming Soon: QRIS --}}
                                    <label class="relative flex cursor-not-allowed items-center justify-between gap-2 rounded-xl border border-[#E6E1EC] bg-gray-100/80 px-3.5 py-2.5 text-xs font-semibold text-gray-400 opacity-75">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="payment_method" value="qris" disabled class="cursor-not-allowed">
                                            <span>QRIS</span>
                                        </span>
                                        <span class="rounded-md bg-gray-200 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-gray-600">Coming Soon</span>
                                    </label>

                                    {{-- Coming Soon: E-Wallet --}}
                                    <label class="relative flex cursor-not-allowed items-center justify-between gap-2 rounded-xl border border-[#E6E1EC] bg-gray-100/80 px-3.5 py-2.5 text-xs font-semibold text-gray-400 opacity-75">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="payment_method" value="ewallet" disabled class="cursor-not-allowed">
                                            <span>E-Wallet / E-Money</span>
                                        </span>
                                        <span class="rounded-md bg-gray-200 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-gray-600">Coming Soon</span>
                                    </label>

                                    {{-- Coming Soon: Bank Account / Direct Debit --}}
                                    <label class="relative flex cursor-not-allowed items-center justify-between gap-2 rounded-xl border border-[#E6E1EC] bg-gray-100/80 px-3.5 py-2.5 text-xs font-semibold text-gray-400 opacity-75">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="payment_method" value="bank_account" disabled class="cursor-not-allowed">
                                            <span>Bank Account</span>
                                        </span>
                                        <span class="rounded-md bg-gray-200 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-gray-600">Coming Soon</span>
                                    </label>
                                </div>
                            </div>

                            <button type="button"
                                class="btn-doku rounded-lg bg-gradient-to-r from-[#BF8AFC] to-[#FA7F94] px-5 py-2.5 text-sm font-semibold text-white hover:opacity-95 disabled:opacity-60 disabled:cursor-not-allowed"
                                data-tagihan-id="{{ $t->id }}"
                                data-payment-url="{{ $t->snap_url ?? '' }}"
                                data-doku-action="{{ route('pendaftar.pembayaran.snap', $t) }}"
                                data-csrf="{{ csrf_token() }}">
                                Bayar via DOKU
                            </button>
                        </form>
                    @endif

                    {{-- Fallback manual: unggah bukti bayar --}}
                    <div class="mt-5 border-t border-[#EFEBF4] pt-4">
                        <p class="text-xs text-[#4E566E]">
                            Belum dapat memakai DOKU? Unggah bukti transfer manual, bukti akan ditinjau panitia.
                        </p>

                        <form method="POST" action="{{ route('pendaftar.pembayaran.bukti', $t) }}"
                            enctype="multipart/form-data" class="mt-2 flex flex-wrap items-end gap-3">
                            @csrf

                            <div>
                                <label for="bukti-{{ $t->id }}" class="block text-xs font-medium text-[#4E566E]">
                                    Bukti transfer (JPG, PNG, PDF, maks 2 MB)
                                </label>
                                <input type="file" id="bukti-{{ $t->id }}" name="bukti"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="mt-1 block text-sm text-[#4E566E]
                                        file:mr-3 file:rounded-lg file:border-0 file:bg-[#F5EFFB] file:px-3 file:py-2 file:text-sm file:font-medium file:text-[#7E22CE]">
                                @error('bukti')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="rounded-lg border border-[#E6E1EC] px-4 py-2 text-sm font-medium text-[#14141F] hover:bg-[#FAF8FC]">
                                Unggah Bukti
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-[#4E566E]">Belum ada tagihan pembayaran.</p>
        @endforelse
    </div>

    {{-- Riwayat pembayaran --}}
    @if($tagihan->isNotEmpty())
        <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
            <h3 class="font-bold text-lg mb-3">Riwayat Pembayaran</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#FAF8FC] text-[#4E566E]">
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
                                <td class="px-3 py-2 text-[#4E566E]">{{ $t->metode ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="tagihan-status rounded-full px-3 py-1 text-xs font-semibold {{ $t->status === 'paid' ? 'bg-green-100 text-green-700' : ($t->status === 'failed' ? 'bg-red-100 text-red-700' : ($t->status === 'expired' ? 'bg-gray-100 text-[#4E566E]' : 'bg-amber-100 text-amber-700')) }}"
                                        data-tagihan-status="{{ $t->id }}" data-status="{{ $t->status }}">
                                        {{ $t->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-[#4E566E]">
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
        // ----------------------------------------------------------------
        // Konfigurasi DOKU Jokul Checkout dari server
        // ----------------------------------------------------------------
        const dokuIsProduction = @json($dokuIsProduction ?? false);
        const dokuCheckoutJsUrl = dokuIsProduction
            ? 'https://jokul.doku.com/jokul-checkout-js/v1/jokul-checkout-1.0.0.js'
            : 'https://sandbox.doku.com/jokul-checkout-js/v1/jokul-checkout-1.0.0.js';

        // Load DOKU Jokul Checkout JS secara dinamis
        (function loadDokuJs() {
            const s = document.createElement('script');
            s.src = dokuCheckoutJsUrl;
            document.head.appendChild(s);
        })();

        // URL halaman pembayaran untuk redirect / reload setelah pop-up ditutup
        const pembayaranUrl = @json(route('pendaftar.pembayaran'));

        // ----------------------------------------------------------------
        // Handler tombol "Bayar via DOKU"
        // ----------------------------------------------------------------
        document.addEventListener('click', async function (e) {
            const btn = e.target.closest('.btn-doku');
            if (!btn) return;

            e.preventDefault();

            const form = btn.closest('form');
            const selectedMethodInput = form ? form.querySelector('input[name="payment_method"]:checked') : null;
            const paymentMethod = selectedMethodInput ? selectedMethodInput.value : 'virtual_account';

            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = 'Memuat DOKU...';

            const action = btn.dataset.dokuAction;
            const csrf   = btn.dataset.csrf;

            try {
                const res = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_method: paymentMethod,
                    }),
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(err.message || 'Gagal membuat transaksi DOKU. Silakan coba lagi.');
                }

                const data = await res.json();
                const paymentUrl = data.payment_url;

                if (!paymentUrl) {
                    throw new Error('URL pembayaran DOKU tidak ditemukan.');
                }

                btn.dataset.paymentUrl = paymentUrl;

                // Panggil pop-up DOKU Jokul Checkout jika library sudah siap, atau fallback redirect ke paymentUrl
                if (typeof window.loadJokulCheckout === 'function') {
                    window.loadJokulCheckout(paymentUrl);
                } else {
                    window.location.href = paymentUrl;
                }
            } catch (err) {
                alert(err.message || 'Terjadi kesalahan saat memuat DOKU. Silakan coba lagi.');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });

        // Tangani event penutupan / penyelesaian pop-up DOKU Checkout agar kembali ke halaman pembayaran
        window.addEventListener('message', function (event) {
            if (!event.data) return;
            const msg = typeof event.data === 'string' ? event.data : (event.data.status || event.data.type || '');
            if (['close', 'closed', 'CLOSE_CHECKOUT', 'SUCCESS', 'PENDING', 'FAILED'].includes(String(msg))) {
                window.location.href = pembayaranUrl;
            }
        });

        // ----------------------------------------------------------------
        // Polling status tagihan setiap 10 detik
        // ----------------------------------------------------------------
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
            expired: 'bg-gray-100 text-[#4E566E]',
            failed: 'bg-red-100 text-red-700',
        };

        async function refresh() {
            try {
                const response = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                let reloadTriggered = false;
                (data.tagihan || []).forEach(function (item) {
                    document.querySelectorAll('[data-tagihan-status="' + item.id + '"]').forEach(function (el) {
                        if (el.dataset.status === item.status) {
                            return;
                        }

                        const oldStatus = el.dataset.status;
                        el.dataset.status = item.status;
                        el.textContent = labels[item.status] || item.status;
                        el.className = 'tagihan-status rounded-full px-3 py-1 text-xs font-semibold '
                            + (classes[item.status] || '');

                        if (oldStatus !== 'paid' && item.status === 'paid' && !reloadTriggered) {
                            reloadTriggered = true;
                            window.location.reload();
                        }
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
