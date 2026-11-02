@extends('layouts.app')
@section('title', 'Dashboard Pendaftar')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Dashboard Saya</h1>
    @if($activePeriod && ! $applications->contains('admission_period_id', $activePeriod->id))
        <a href="{{ route('applicant.application.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">
            + Buat Pendaftaran
        </a>
    @endif
</div>

@if($applications->isEmpty())
    <div class="bg-white shadow rounded-xl p-12 text-center text-gray-500">
        <div class="text-5xl mb-4">📋</div>
        <p class="text-lg font-medium mb-2">Belum ada pendaftaran</p>
        @if($activePeriod)
            <p class="text-sm mb-4">Periode {{ $activePeriod->name }} sedang buka. Daftarkan dirimu sekarang!</p>
            <a href="{{ route('applicant.application.create') }}"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700">
                Mulai Pendaftaran
            </a>
        @else
            <p class="text-sm">Tidak ada periode pendaftaran yang aktif saat ini.</p>
        @endif
    </div>
@else
    @foreach($applications as $application)
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-sm text-gray-500 mb-1">{{ $application->admissionPeriod->name }}</div>
                <div class="font-bold text-lg">{{ $application->full_name }}</div>
                <div class="text-gray-600">{{ $application->studyProgram->name }}</div>
                @if($application->registration_number)
                    <div class="text-sm font-mono text-blue-600 mt-1">{{ $application->registration_number }}</div>
                @endif
            </div>
            <div class="text-right">
                @php
                    $colors = [
                        'draft' => 'bg-gray-100 text-gray-600',
                        'submitted' => 'bg-blue-100 text-blue-700',
                        'under_review' => 'bg-yellow-100 text-yellow-700',
                        'revision_required' => 'bg-orange-100 text-orange-700',
                        'passed' => 'bg-green-100 text-green-700',
                        'not_passed' => 'bg-red-100 text-red-700',
                    ];
                    $color = $colors[$application->status] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                    {{ $application->status_label }}
                </span>
            </div>
        </div>

        @if($application->status === 'revision_required' && $application->admin_note)
            <div class="mt-3 bg-orange-50 border border-orange-200 rounded p-3 text-sm text-orange-800">
                <strong>Catatan Admin:</strong> {{ $application->admin_note }}
            </div>
        @endif

        <div class="mt-4 flex gap-2 flex-wrap">
            @if($application->isEditable())
                <a href="{{ route('applicant.application.edit', $application) }}"
                    class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Edit Data</a>
                <a href="{{ route('applicant.document.index', $application) }}"
                    class="text-sm bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">Kelola Dokumen</a>
                <a href="{{ route('applicant.application.summary', $application) }}"
                    class="text-sm border border-blue-600 text-blue-600 px-3 py-1 rounded hover:bg-blue-50">Ringkasan & Submit</a>
            @endif
            <a href="{{ route('applicant.application.status', $application) }}"
                class="text-sm border border-gray-300 text-gray-600 px-3 py-1 rounded hover:bg-gray-50">Lihat Status</a>
        </div>
    </div>
    @endforeach
@endif
@endsection