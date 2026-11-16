@extends('layouts.app')
@section('title', 'Dashboard Pendaftar')

@section('content')
{{-- Heading --}}
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Dashboard Saya</h1>
    <p class="text-xs sm:text-sm text-[#818CA2] mt-0.5">Kelola data pendaftaran dan pantau status kelulusan kamu</p>
</div>

{{-- Applications List / Empty State --}}
@if($applications->isEmpty())
    <div class="bg-white shadow-xs border border-[#E5EAFC] rounded-2xl p-10 sm:p-14 text-center">
        <div class="w-16 h-16 rounded-2xl bg-[#F2F5FF] text-[#5F4AF7] mx-auto flex items-center justify-center mb-5">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </div>
        <h3 class="text-lg sm:text-xl font-bold text-black mb-1.5">Belum ada pendaftaran</h3>
        @if($activePeriod)
            <p class="text-sm text-[#818CA2] max-w-md mx-auto mb-6">
                Periode <strong>{{ $activePeriod->name }}</strong> sedang dibuka. Mulai lengkapi berkas dan formulir kamu sekarang!
            </p>
            <a href="{{ route('applicant.application.create') }}"
                class="inline-flex items-center gap-2 bg-white border border-[#C5CEE0] text-black px-7 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50 hover:border-gray-400 shadow-xs transition">
                <span>Mulai Pendaftaran</span>
                <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        @else
            <p class="text-sm text-[#818CA2]">Saat ini belum ada periode pendaftaran yang aktif.</p>
        @endif
    </div>
@else
    <div class="space-y-5">
        @foreach($applications as $application)
            <div class="bg-white shadow-xs border border-[#E5EAFC] rounded-2xl p-6 sm:p-7 hover:shadow-md transition">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-[#5F4AF7] mb-1">
                            {{ $application->admissionPeriod->name }}
                        </div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-black tracking-tight">
                            {{ $application->full_name }}
                        </h2>
                        <div class="text-[#818CA2] text-sm mt-0.5 font-medium">
                            {{ $application->studyProgram->name }} ({{ $application->studyProgram->faculty }})
                        </div>
                        @if($application->registration_number)
                            <div class="inline-block font-mono text-xs font-bold text-[#5F4AF7] bg-[#F2F5FF] border border-[#5F4AF7]/20 px-3 py-1 rounded-lg mt-3">
                                {{ $application->registration_number }}
                            </div>
                        @endif
                    </div>
                    <div>
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
                        <span class="px-4 py-1.5 rounded-full text-xs font-semibold inline-block {{ $badgeStyle }}">
                            {{ $application->status_label }}
                        </span>
                    </div>
                </div>

                {{-- Admin Revision Note Alert --}}
                @if($application->status === 'revision_required' && $application->admin_note)
                    <div class="mt-5 bg-orange-50 border border-orange-200 rounded-xl p-4 text-sm text-orange-900 flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.526ZM12 18.75h.007v.008H12v-.008Z" />
                        </svg>
                        <div>
                            <span class="font-bold">Catatan Perbaikan dari Admin:</span>
                            <p class="mt-0.5">{{ $application->admin_note }}</p>
                        </div>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="mt-6 pt-5 border-t border-[#E5EAFC] flex flex-wrap items-center gap-3">
                    @if($application->isEditable())
                        <a href="{{ route('applicant.application.edit', $application) }}"
                            class="bg-[#5F4AF7] hover:bg-[#4E39E5] text-white px-4 py-2 rounded-xl text-xs font-semibold transition shadow-xs">
                            Edit Formulir
                        </a>
                        <a href="{{ route('applicant.document.index', $application) }}"
                            class="bg-white border border-[#E5EAFC] text-black hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-xs font-semibold transition">
                            Kelola Dokumen
                        </a>
                        <a href="{{ route('applicant.application.summary', $application) }}"
                            class="border border-[#5F4AF7] text-[#5F4AF7] hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-xs font-semibold transition">
                            Ringkasan & Kirim
                        </a>
                    @endif
                    <a href="{{ route('applicant.application.status', $application) }}"
                        class="bg-white border border-[#E5EAFC] text-[#818CA2] hover:text-black hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-xs font-semibold transition ml-auto">
                        Lihat Status Lengkap →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
