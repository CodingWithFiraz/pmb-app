@extends('layouts.app')
@section('title', 'Pembayaran UKT')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
                <a href="{{ route('applicant.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                <span>/</span>
                <span class="text-[#606ADF]">Pembayaran</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Pembayaran UKT</h1>
            <p class="text-sm text-[#818CA2] mt-1">Langkah lanjutan setelah dokumen pendaftaran dinyatakan lulus verifikasi.</p>
        </div>
        <a href="{{ route('applicant.dashboard') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#606ADF] bg-white border border-[#DBDCFA] px-4 py-2 rounded-xl hover:bg-[#F2F5FF] transition shadow-xs">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-[#F0FDF4] border border-[#16A34A]/20 rounded-2xl p-4 sm:p-5 mb-5 text-[#16A34A] text-sm flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            Dokumen kamu sudah <strong>lulus verifikasi</strong>. Silakan selesaikan pembayaran Uang Kuliah Tunggal (UKT) sesuai golongan yang telah ditetapkan oleh panitia PMB.
        </div>
    </div>

    {{-- Data Pendaftaran Card --}}
    <div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs p-6 mb-5">
        <h2 class="text-base font-bold text-black mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#606ADF]"></span>
            Data Pendaftaran
        </h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div>
                <dt class="text-xs uppercase tracking-wider text-[#818CA2]">Nama Pendaftar</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->full_name }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wider text-[#818CA2]">Nomor Pendaftaran</dt>
                <dd class="font-mono font-bold text-[#606ADF] mt-0.5">{{ $application->registration_number ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wider text-[#818CA2]">Program Studi Pilihan</dt>
                <dd class="font-medium text-black mt-0.5">{{ $application->studyProgram->name }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wider text-[#818CA2]">Periode Penerimaan</dt>
                <dd class="font-medium text-black mt-0.5">{{ $application->admissionPeriod->name }}</dd>
            </div>
        </dl>
    </div>

    @if($application->ukt_golongan && $amount)
        {{-- Golongan UKT Card --}}
        <div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs p-6 mb-5">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <div class="text-xs uppercase tracking-wider text-[#818CA2]">Golongan UKT Ditetapkan</div>
                    <div class="text-2xl font-extrabold text-[#16A34A] mt-1">{{ $golonganLabels[$application->ukt_golongan] }}</div>
                    <p class="text-xs text-[#818CA2] mt-1">{{ $golonganDescriptions[$application->ukt_golongan] }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-xs uppercase tracking-wider text-[#818CA2]">Total Tagihan UKT</div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-black mt-1">Rp {{ number_format($amount, 0, ',', '.') }}</div>
                    @php
                        $pay = $application->latestPayment;
                        $payLabel = $pay?->status_label ?? $application->payment_status_label;
                        $isSettled = ($pay?->status === 'settlement' || $application->payment_status === 'verified');
                    @endphp
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $isSettled ? 'bg-[#F0FDF4] text-[#16A34A] border border-[#16A34A]/20' : 'bg-[#FFFBEB] text-[#B45309] border border-[#F59E0B]/20' }}">
                        {{ $payLabel }}
                    </span>
                </div>
            </div>

            {{-- Accordion Tabel Golongan UKT --}}
            <div x-data="{ open: false }" class="border border-[#E5EAFC] rounded-xl overflow-hidden">
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 bg-[#F2F5FF]/60 hover:bg-[#F2F5FF] transition text-xs font-bold text-gray-700">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#606ADF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lihat Referensi Tabel Golongan UKT
                    </span>
                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-4 h-4 text-gray-400 transition-transform duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="border-t border-[#E5EAFC]">
                    <table class="w-full text-xs sm:text-sm">
                        <thead class="bg-[#F2F5FF]/40 text-left text-[#818CA2] border-b border-[#E5EAFC]">
                            <tr>
                                <th class="px-4 py-2.5 font-semibold">Golongan</th>
                                <th class="px-4 py-2.5 font-semibold">Keterangan</th>
                                <th class="px-4 py-2.5 text-right font-semibold">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5EAFC]">
                            @foreach($golonganList as $golongan => $nominal)
                                <tr class="{{ $golongan == $application->ukt_golongan ? 'bg-[#F0FDF4]/70 font-semibold' : 'hover:bg-[#F2F5FF]/30' }}">
                                    <td class="px-4 py-2.5 flex items-center gap-2 text-black">
                                        {{ $golonganLabels[$golongan] }}
                                        @if($golongan == $application->ukt_golongan)
                                            <span class="text-[11px] bg-[#16A34A] text-white rounded-full px-2 py-0.5">Penetapan Kamu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-[#818CA2]">{{ $golonganDescriptions[$golongan] }}</td>
                                    <td class="px-4 py-2.5 text-right font-bold text-black">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Checkout Section --}}
        <div class="bg-white rounded-2xl border border-[#E5EAFC] shadow-xs p-6">
            <h2 class="text-base font-bold text-black mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#606ADF]"></span>
                Pembayaran Online Midtrans
            </h2>

            @if($isPaid)
                <div class="flex items-center gap-3 p-4 bg-[#F0FDF4] border border-[#16A34A]/20 rounded-xl">
                    <svg class="w-6 h-6 text-[#16A34A] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <div class="font-bold text-[#16A34A]">Pembayaran UKT Telah Lunas</div>
                        <div class="text-xs text-[#16A34A]/80 mt-0.5">
                            Terima kasih, pembayaran UKT Anda telah terkonfirmasi. Silakan pantau pengumuman lebih lanjut.
                        </div>
                    </div>
                </div>

            @elseif($snapToken)
                <p class="text-sm text-[#818CA2] mb-5">
                    Klik tombol di bawah untuk memilih metode pembayaran (QRIS, Transfer Bank/VA, E-Wallet, Kartu) dan menyelesaikan transaksi secara real-time via Midtrans.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button
                        id="pay-btn"
                        type="button"
                        class="w-full sm:w-auto bg-black hover:bg-gray-800 text-white px-7 py-3 rounded-xl font-bold text-sm shadow-xs transition flex items-center justify-center gap-2.5">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Bayar UKT Sekarang</span>
                    </button>
                    <span class="text-xs text-[#818CA2] flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#16A34A]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Transaksi aman diproses oleh Midtrans Payment Gateway
                    </span>
                </div>

            @else
                <p class="text-sm text-[#818CA2] mb-4">
                    Terjadi kendala saat menyiapkan sesi pembayaran. Silakan klik tombol di bawah untuk mencoba kembali.
                </p>
                <a href="{{ route('applicant.payment.show', $application) }}"
                   class="inline-block bg-[#F2F5FF] hover:bg-[#E5EAFC] text-black border border-[#DBDCFA] px-5 py-2.5 rounded-xl font-semibold text-xs transition">
                    Coba Lagi
                </a>
            @endif
        </div>
    @else
        <div class="bg-[#FFFBEB] border border-[#F59E0B]/20 rounded-2xl p-6 text-[#B45309] text-sm">
            <h3 class="font-bold mb-1">Golongan UKT Belum Ditetapkan</h3>
            <p class="text-xs text-[#B45309]/90">
                Panitia PMB sedang memverifikasi data dan menentukan golongan UKT kamu. Silakan periksa kembali dashboard secara berkala.
            </p>
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
