@extends('layouts.app')
@section('title', 'Upload Dokumen Persyaratan')
@section('body-class', 'bg-soft-gradient')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Page Heading --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Upload Dokumen Persyaratan</h1>
        <p class="text-xs sm:text-sm text-gray-700 mt-1">Pendaftar: <strong class="text-black">{{ $application->full_name }}</strong> · Periode: <strong class="text-black">{{ $application->admissionPeriod->name }}</strong></p>
    </div>

    {{-- Format Guidelines Notice --}}
    <div class="bg-[#F3F3FF] rounded-2xl p-5 border border-[#E5EAFC] shadow-xs flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-[#FEFDFF] border border-[#DBDCFA] text-[#606ADF] flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
        </div>
        <div class="text-xs sm:text-sm text-gray-700">
            Format file yang didukung: <span class="font-bold text-black">PDF, JPG, JPEG, PNG</span>. Batas ukuran berkas maksimal: <span class="font-bold text-black">2 MB per dokumen</span>.
        </div>
    </div>

    @if(! $application->isEditable())
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl p-4 text-sm font-medium">
            ⚠️ Dokumen tidak dapat diubah lagi karena status pendaftaran saat ini adalah <strong>{{ $application->status_label }}</strong>.
        </div>
    @endif

    {{-- Document Cards List --}}
    <div class="space-y-4">
        @foreach($requiredDocuments as $type => $label)
            @php $doc = $documents[$type] ?? null; @endphp
            <div class="bg-[#F3F3FF] rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs transition">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-3">
                    <div>
                        <h3 class="font-bold text-base text-black flex items-center gap-2">
                            <span>{{ $label }}</span>
                            @if($doc)
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            @endif
                        </h3>
                        @if($doc)
                            <div class="text-xs text-[#818CA2] mt-1 flex items-center gap-2">
                                <span class="font-medium text-black">{{ $doc->original_name }}</span>
                                <span>•</span>
                                <span>{{ round($doc->file_size / 1024, 1) }} KB</span>
                                <span>•</span>
                                <span>Diunggah {{ $doc->uploaded_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="mt-2">
                                @if($doc->verification_status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-[#F0FDF4] text-[#16A34A] border border-[#16A34A]/20">
                                        ✓ Dokumen Terverifikasi
                                    </span>
                                @elseif($doc->verification_status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-[#FEF2F2] text-[#DC2626] border border-[#DC2626]/20">
                                        ✗ Ditolak: {{ $doc->admin_note }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#2563EB]/20">
                                        Menunggu verifikasi admin
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="text-xs text-red-500 font-medium mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                <span>Belum diunggah</span>
                            </div>
                        @endif
                    </div>
                    @if($doc)
                        <a href="{{ route('applicant.document.download', $doc) }}"
                            class="bg-[#FEFDFF] text-[#606ADF] border border-[#DBDCFA] hover:bg-[#606ADF] hover:text-white px-3.5 py-1.5 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Unduh / Lihat</span>
                        </a>
                    @endif
                </div>

                {{-- Upload Form --}}
                @if($application->isEditable())
                    <form method="POST" action="{{ route('applicant.document.store', $application) }}"
                        enctype="multipart/form-data" class="mt-4 pt-4 border-t border-[#E5EAFC]">
                        @csrf
                        <input type="hidden" name="document_type" value="{{ $type }}">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required
                                class="flex-1 text-xs text-black bg-[#F3F3FF] border border-[#DBDCFA] rounded-xl px-3 py-2 file:mr-3 file:py-1 file:px-3.5 file:rounded-lg file:border file:border-[#DBDCFA] file:bg-[#F3F3FF] file:text-black hover:file:bg-white file:text-xs file:font-semibold file:transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#606ADF]">
                            <button type="submit"
                                class="bg-black hover:bg-gray-800 text-white px-5 py-2 rounded-xl text-xs font-semibold transition shrink-0 inline-flex items-center justify-center gap-1.5 shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                <span>{{ $doc ? 'Ganti File' : 'Upload File' }}</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Navigation Bottom Buttons --}}
    <div class="flex flex-wrap items-center justify-between gap-4 pt-3">
        <a href="{{ route('applicant.application.edit', $application) }}" 
           class="bg-[#F3F3FF] border border-[#DBDCFA] text-gray-800 hover:text-black hover:bg-white px-6 py-3 rounded-xl font-semibold text-sm transition">
            ← Edit Formulir
        </a>
        <a href="{{ route('applicant.application.summary', $application) }}" 
           class="bg-[#F3F3FF] border border-[#DBDCFA] text-black hover:bg-white px-7 py-3 rounded-xl font-semibold text-sm transition shadow-2xs inline-flex items-center gap-2">
            <span>Lanjut ke Ringkasan & Kirim</span>
            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</div>
@endsection
