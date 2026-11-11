@extends('layouts.app')
@section('title', 'Pembayaran UKT')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Pembayaran UKT</h1>
            <p class="text-sm text-gray-500 mt-1">Langkah lanjutan setelah dokumen dinyatakan lulus.</p>
        </div>
        <a href="{{ route('applicant.dashboard') }}" class="text-blue-600 hover:underline text-sm">← Dashboard</a>
    </div>

    <div class="bg-green-50 border border-green-300 rounded-xl p-4 mb-4 text-green-800">
        Dokumen kamu sudah <strong>lulus verifikasi</strong>. Silakan lanjutkan dengan pembayaran Uang Kuliah Tunggal sesuai golongan yang ditetapkan.
    </div>

    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <h2 class="font-bold text-blue-700 mb-3">Data Pendaftaran</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-gray-500">Nama</dt>
            <dd class="font-medium">{{ $application->full_name }}</dd>
            <dt class="text-gray-500">Nomor Pendaftaran</dt>
            <dd class="font-mono text-blue-700">{{ $application->registration_number ?? '-' }}</dd>
            <dt class="text-gray-500">Program Studi</dt>
            <dd>{{ $application->studyProgram->name }}</dd>
            <dt class="text-gray-500">Periode</dt>
            <dd>{{ $application->admissionPeriod->name }}</dd>
        </dl>
    </div>

    @if($application->ukt_golongan && $amount)
        <div class="bg-white shadow rounded-xl p-6 mb-4">
            <div class="flex justify-between items-start gap-4 mb-5">
                <div>
                    <div class="text-sm text-gray-500">Golongan UKT kamu</div>
                    <div class="text-2xl font-bold text-emerald-700">{{ $golonganLabels[$application->ukt_golongan] }}</div>
                    <p class="text-sm text-gray-600 mt-1">{{ $golonganDescriptions[$application->ukt_golongan] }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Tagihan</div>
                    <div class="text-2xl font-bold">Rp {{ number_format($amount, 0, ',', '.') }}</div>
                    @php
                        $pay = $application->latestPayment;
                        $payLabel = $pay?->status_label ?? $application->payment_status_label;
                    @endphp
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium
                        {{ ($pay?->status === 'settlement' || $application->payment_status === 'verified') ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-800' }}">
                        {{ $payLabel }}
                    </span>
                </div>
            </div>

            {{-- ═══ Accordion Tabel Golongan UKT ═══ --}}
            <div x-data="{ open: false }" class="border rounded-lg overflow-hidden">
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition text-sm font-semibold text-gray-700">
                    <span>ℹ️ Lihat Tabel Golongan UKT</span>
                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="border-t">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left text-gray-500">
                            <tr>
                                <th class="px-4 py-2">Golongan</th>
                                <th class="px-4 py-2">Keterangan</th>
                                <th class="px-4 py-2 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($golonganList as $golongan => $nominal)
                                <tr class="border-t {{ $golongan == $application->ukt_golongan ? 'bg-emerald-50 font-medium' : 'hover:bg-gray-50' }}">
                                    <td class="px-4 py-2 flex items-center gap-2">
                                        {{ $golonganLabels[$golongan] }}
                                        @if($golongan == $application->ukt_golongan)
                                            <span class="text-xs bg-emerald-600 text-white rounded-full px-2 py-0.5">Kamu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">{{ $golonganDescriptions[$golongan] }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══ Checkout Section ═══ --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="font-bold text-blue-700 mb-2">Checkout Pembayaran</h2>

            @if($isPaid)
                {{-- Sudah Lunas --}}
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-300 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <div class="font-semibold text-green-800">Pembayaran UKT Telah Lunas</div>
                        <div class="text-sm text-green-700 mt-0.5">
                            Terima kasih, pembayaran kamu sudah diterima. Selamat bergabung!
                        </div>
                    </div>
                </div>

            @elseif($snapToken)
                {{-- Tombol Bayar Snap --}}
                <p class="text-sm text-gray-600 mb-4">
                    Klik tombol di bawah untuk memilih metode pembayaran dan menyelesaikan transaksi UKT secara real-time melalui Midtrans Snap.
                </p>
                <button
                    id="pay-btn"
                    type="button"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold shadow transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Bayar UKT Sekarang
                </button>
                <p class="text-xs text-gray-400 mt-2">Transaksi diproses aman oleh Midtrans.</p>

            @else
                {{-- Gagal generate token --}}
                <p class="text-sm text-gray-600 mb-4">
                    Terjadi kendala saat menyiapkan halaman pembayaran. Silakan muat ulang halaman atau hubungi panitia PMB.
                </p>
                <a href="{{ route('applicant.payment.show', $application) }}"
                   class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition">
                    Coba Lagi
                </a>
            @endif
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-amber-900">
            Golongan UKT belum ditetapkan oleh admin. Hubungi panitia PMB untuk penetapan golongan 1–5.
        </div>
    @endif
</div>
@endsection

@push('scripts')
{{-- Alpine.js untuk accordion --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@if(!$isPaid && isset($snapToken) && $snapToken)
{{-- Midtrans Snap JS --}}
<script src="{{ config('midtrans.is_production')
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ $clientKey }}"></script>

<script>
    document.getElementById('pay-btn')?.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                console.log('Midtrans success:', result);
                window.location.reload();
            },
            onPending: function (result) {
                console.log('Midtrans pending:', result);
                window.location.reload();
            },
            onError: function (result) {
                console.error('Midtrans error:', result);
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function () {
                console.log('Snap ditutup sebelum menyelesaikan pembayaran.');
            }
        });
    });
</script>
@endif
@endpush
