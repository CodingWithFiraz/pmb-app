@extends('layouts.app')
@section('title', 'Status Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Status Pendaftaran</h1>

    {{-- Info Utama --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        @if($application->registration_number)
            <div class="text-center mb-4">
                <div class="text-sm text-gray-500">Nomor Pendaftaran</div>
                <div class="text-2xl font-bold font-mono text-blue-700">{{ $application->registration_number }}</div>
            </div>
        @endif

        @php
            $colors = [
                'draft' => ['bg-gray-100', 'text-gray-700'],
                'submitted' => ['bg-blue-100', 'text-blue-700'],
                'under_review' => ['bg-yellow-100', 'text-yellow-700'],
                'revision_required' => ['bg-orange-100', 'text-orange-700'],
                'passed' => ['bg-green-100', 'text-green-700'],
                'not_passed' => ['bg-red-100', 'text-red-700'],
            ];
            [$bg, $text] = $colors[$application->status] ?? ['bg-gray-100', 'text-gray-700'];
        @endphp

        <div class="text-center">
            <span class="px-6 py-2 rounded-full text-lg font-semibold {{ $bg }} {{ $text }}">
                {{ $application->status_label }}
            </span>
        </div>

        @if($application->status === 'passed')
            <div class="mt-4 bg-green-50 border border-green-300 rounded-xl p-4 text-center text-green-800">
                🎉 <strong>Selamat!</strong> Kamu dinyatakan LULUS seleksi penerimaan mahasiswa baru.
                @if($application->admin_note)
                    <p class="mt-1 text-sm">{{ $application->admin_note }}</p>
                @endif
                <div class="mt-4">
                    <a href="{{ route('applicant.payment.show', $application) }}"
                        class="inline-block bg-emerald-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-emerald-700">
                        Lanjut ke Pembayaran UKT
                    </a>
                </div>
            </div>
        @elseif($application->status === 'not_passed')
            <div class="mt-4 bg-red-50 border border-red-300 rounded-xl p-4 text-center text-red-800">
                Mohon maaf, kamu dinyatakan tidak lulus pada seleksi ini.
                @if($application->admin_note)
                    <p class="mt-1 text-sm">{{ $application->admin_note }}</p>
                @endif
            </div>
        @elseif($application->status === 'revision_required' && $application->admin_note)
            <div class="mt-4 bg-orange-50 border border-orange-300 rounded-xl p-4 text-orange-800">
                <strong>⚠️ Perlu Perbaikan:</strong> {{ $application->admin_note }}
                <div class="mt-2">
                    <a href="{{ route('applicant.application.edit', $application) }}"
                        class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-600">
                        Perbaiki Sekarang
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Riwayat Status --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="font-bold text-blue-700 mb-4">Riwayat Status</h2>
        @if($application->statusHistories->isEmpty())
            <p class="text-gray-500 text-sm">Belum ada riwayat.</p>
        @else
            <div class="space-y-3">
                @foreach($application->statusHistories as $history)
                    <div class="flex gap-3 text-sm">
                        <div class="text-gray-400 text-xs pt-0.5 w-32 shrink-0">{{ $history->created_at->format('d M Y H:i') }}</div>
                        <div>
                            <div class="font-medium">
                                @if($history->from_status)
                                    <span class="text-gray-500">{{ $history->from_status }}</span>
                                    → <span class="text-blue-700">{{ $history->to_status }}</span>
                                @else
                                    <span class="text-blue-700">{{ $history->to_status }}</span>
                                @endif
                            </div>
                            @if($history->note)
                                <div class="text-gray-600 mt-0.5">{{ $history->note }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('applicant.dashboard') }}" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
</div>
@endsection