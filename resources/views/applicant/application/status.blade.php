@extends('layouts.app')
@section('title', 'Status Pendaftaran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header & Back Link --}}
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
                <a href="{{ route('applicant.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                <span>/</span>
                <span class="text-[#606ADF]">Status Pendaftaran</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Status Pendaftaran</h1>
        </div>
        <a href="{{ route('applicant.dashboard') }}" 
           class="bg-white border border-[#E5EAFC] text-gray-700 hover:text-black hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-sm font-semibold transition inline-flex items-center gap-2">
            <svg class="w-4 h-4 text-[#818CA2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- Main Status Card --}}
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs text-center">
        @if($application->registration_number)
            <div class="mb-4">
                <span class="text-xs uppercase tracking-wider font-semibold text-[#818CA2] block">Nomor Pendaftaran</span>
                <span class="font-mono text-2xl sm:text-3xl font-extrabold text-[#606ADF] tracking-tight block mt-1">
                    {{ $application->registration_number }}
                </span>
            </div>
        @endif

        <div class="my-4">
            @php
                $statusStyles = [
                    'draft' => 'bg-gray-100 text-gray-700',
                    'submitted' => 'bg-blue-50 text-blue-700 border border-blue-200',
                    'under_review' => 'bg-amber-50 text-amber-700 border border-amber-200',
                    'revision_required' => 'bg-orange-50 text-orange-700 border border-orange-200',
                    'passed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                    'not_passed' => 'bg-red-50 text-red-700 border border-red-200',
                ];
                $badgeStyle = $statusStyles[$application->status] ?? 'bg-gray-100 text-gray-700';
            @endphp
            <span class="px-5 py-2 rounded-full text-sm sm:text-base font-bold inline-block {{ $badgeStyle }}">
                {{ $application->status_label }}
            </span>
        </div>

        {{-- Status Details Banner --}}
        @if($application->status === 'passed')
            <div class="mt-6 bg-[#F0FDF4] border border-[#16A34A]/25 rounded-2xl p-6 text-center text-[#15803D]">
                <div class="text-3xl mb-2">🎉</div>
                <h3 class="text-lg font-extrabold text-[#15803D]">Selamat! Kamu Dinyatakan LULUS</h3>
                <p class="text-sm text-[#16A34A] mt-1 max-w-md mx-auto">
                    Selamat, kamu telah berhasil lolos pada tahapan seleksi Penerimaan Mahasiswa Baru!
                </p>
                @if($application->admin_note)
                    <div class="mt-4 p-3 bg-white/80 rounded-xl border border-[#16A34A]/20 text-xs text-gray-800">
                        <strong>Pesan dari Panitia:</strong> {{ $application->admin_note }}
                    </div>
                @endif
            </div>
        @elseif($application->status === 'not_passed')
            <div class="mt-6 bg-[#FEF2F2] border border-[#DC2626]/20 rounded-2xl p-6 text-center text-[#B91C1C]">
                <div class="text-3xl mb-2">📋</div>
                <h3 class="text-lg font-extrabold text-[#B91C1C]">Belum Lolos Seleksi</h3>
                <p class="text-sm text-[#DC2626] mt-1 max-w-md mx-auto">
                    Mohon maaf, kamu belum dinyatakan lulus pada seleksi penerimaan mahasiswa baru periode ini.
                </p>
                @if($application->admin_note)
                    <div class="mt-4 p-3 bg-white/80 rounded-xl border border-[#DC2626]/20 text-xs text-gray-800">
                        <strong>Catatan Panitia:</strong> {{ $application->admin_note }}
                    </div>
                @endif
            </div>
        @elseif($application->status === 'revision_required' && $application->admin_note)
            <div class="mt-6 bg-orange-50 border border-orange-200 rounded-2xl p-6 text-left text-orange-900">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-orange-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.526ZM12 18.75h.007v.008H12v-.008Z" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-extrabold text-base text-orange-900">Perbaikan Berkas / Data Diperlukan</h3>
                        <p class="text-xs sm:text-sm text-orange-800 mt-1">{{ $application->admin_note }}</p>
                        <div class="mt-4">
                            <a href="{{ route('applicant.application.edit', $application) }}"
                                class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-xl text-xs font-semibold transition shadow-xs">
                                <span>Perbaiki Formulir Sekarang</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($application->status === 'submitted')
            <div class="mt-6 bg-[#EFF6FF] border border-[#2563EB]/20 rounded-2xl p-6 text-center text-[#1E40AF]">
                <h3 class="text-base font-bold text-[#1E40AF]">Pendaftaran Sedang Menunggu Pemeriksaan</h3>
                <p class="text-xs sm:text-sm text-[#3B82F6] mt-1 max-w-md mx-auto">
                    Berkas dan data pendaftaran kamu telah berhasil dikirim. Panitia PMB akan segera memeriksa kelengkapan berkas kamu.
                </p>
            </div>
        @elseif($application->status === 'under_review')
            <div class="mt-6 bg-[#FEFCE8] border border-[#D97706]/20 rounded-2xl p-6 text-center text-[#92400E]">
                <h3 class="text-base font-bold text-[#92400E]">Pendaftaran Sedang Diperiksa</h3>
                <p class="text-xs sm:text-sm text-[#D97706] mt-1 max-w-md mx-auto">
                    Data dan berkas kamu sedang diverifikasi oleh panitia PMB. Harap pantau berkala status pendaftaran kamu.
                </p>
            </div>
        @endif
    </div>

    {{-- Status History Timeline Card --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Riwayat Status Pendaftaran</h2>
        @if($application->statusHistories->isEmpty())
            <p class="text-[#818CA2] text-sm">Belum ada catatan riwayat status.</p>
        @else
            <div class="space-y-3">
                @foreach($application->statusHistories as $history)
                    <div class="flex items-start gap-4 p-3.5 rounded-xl bg-[#F2F5FF]/50 border border-[#E5EAFC]">
                        <div class="text-xs text-[#818CA2] w-36 shrink-0 pt-0.5 font-medium">
                            {{ $history->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="flex-1 text-sm">
                            <div class="font-semibold text-black flex items-center gap-1.5">
                                @if($history->from_status)
                                    <span class="text-[#818CA2] font-normal">{{ $history->from_status }}</span>
                                    <span class="text-[#818CA2]">→</span>
                                    <span class="text-[#606ADF]">{{ $history->to_status }}</span>
                                @else
                                    <span class="text-[#606ADF]">{{ $history->to_status }}</span>
                                @endif
                            </div>
                            @if($history->note)
                                <div class="text-gray-700 text-xs mt-1 bg-white p-2.5 rounded-lg border border-[#E5EAFC]">
                                    {{ $history->note }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
