@extends('layouts.app')
@section('title', 'Ringkasan Pendaftaran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
            <a href="{{ route('applicant.dashboard') }}" class="hover:text-black transition">Dashboard</a>
            <span>/</span>
            <span class="text-[#5F4AF7]">Ringkasan</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Ringkasan Pendaftaran</h1>
        <p class="text-xs sm:text-sm text-[#818CA2] mt-0.5">Periksa kembali data diri dan kelengkapan dokumen kamu sebelum dikirim</p>
    </div>

    {{-- Stepper Indicator --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-[#E5EAFC] shadow-xs">
        <div class="grid grid-cols-3 gap-2 text-center text-xs sm:text-sm font-semibold">
            <a href="{{ route('applicant.application.edit', $application) }}" class="flex items-center justify-center gap-2 text-[#16A34A] pb-1 border-b-2 border-[#16A34A]">
                <span class="w-6 h-6 rounded-full bg-[#F0FDF4] text-[#16A34A] flex items-center justify-center text-xs font-bold">✓</span>
                <span>Isi Formulir</span>
            </a>
            <a href="{{ route('applicant.document.index', $application) }}" class="flex items-center justify-center gap-2 text-[#16A34A] pb-1 border-b-2 border-[#16A34A]">
                <span class="w-6 h-6 rounded-full bg-[#F0FDF4] text-[#16A34A] flex items-center justify-center text-xs font-bold">✓</span>
                <span class="hidden sm:inline">Upload</span> Dokumen
            </a>
            <div class="flex items-center justify-center gap-2 text-[#5F4AF7] pb-1 border-b-2 border-[#5F4AF7]">
                <span class="w-6 h-6 rounded-full bg-[#F2F5FF] text-[#5F4AF7] flex items-center justify-center text-xs font-bold">3</span>
                <span class="hidden sm:inline">Ringkasan &</span> Kirim
            </div>
        </div>
    </div>

    {{-- Data Utama --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <div class="flex items-center justify-between pb-3 border-b border-[#E5EAFC] mb-4">
            <h2 class="font-bold text-lg text-black">Informasi Formulir Pendaftaran</h2>
            @if($application->isEditable())
                <a href="{{ route('applicant.application.edit', $application) }}" 
                   class="text-xs font-semibold text-[#5F4AF7] hover:underline">
                    Edit Data →
                </a>
            @endif
        </div>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-sm">
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Periode Penerimaan</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->admissionPeriod->name }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Pilihan Program Studi</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->studyProgram->name }} ({{ $application->studyProgram->faculty }})</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Nama Lengkap</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->full_name }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Jenis Kelamin</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Tempat / Tanggal Lahir</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->birth_place }}, {{ $application->birth_date?->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Nomor HP</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->phone }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Asal Sekolah</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->school_name }} ({{ $application->school_city }})</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Tahun Kelulusan</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->graduation_year }}</dd>
            </div>
        </dl>
    </div>

    {{-- Dokumen yang Diunggah --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <div class="flex items-center justify-between pb-3 border-b border-[#E5EAFC] mb-4">
            <h2 class="font-bold text-lg text-black">Kelengkapan Dokumen</h2>
            @if($application->isEditable())
                <a href="{{ route('applicant.document.index', $application) }}" 
                   class="text-xs font-semibold text-[#5F4AF7] hover:underline">
                    Kelola Dokumen →
                </a>
            @endif
        </div>
        @if($application->documents->isEmpty())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <span>Belum ada dokumen yang diunggah. Pastikan mengunggah dokumen persyaratan sebelum submit.</span>
            </div>
        @else
            <div class="space-y-2">
                @foreach($application->documents as $doc)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#F2F5FF]/50 border border-[#E5EAFC]">
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-[#F0FDF4] text-[#16A34A] flex items-center justify-center text-xs font-bold shrink-0">✓</span>
                            <span class="font-semibold text-sm text-black">{{ $doc->label }}</span>
                            <span class="text-xs text-[#818CA2]">({{ $doc->original_name }})</span>
                        </div>
                        <span class="text-xs text-[#818CA2]">{{ round($doc->file_size / 1024, 1) }} KB</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Submit Section --}}
    @if($application->isEditable())
        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
            <h2 class="font-bold text-lg text-black mb-2">Kirim Formulir Pendaftaran</h2>
            <p class="text-xs sm:text-sm text-[#818CA2] mb-5 leading-relaxed">
                Dengan menekan tombol kirim, kamu menyatakan bahwa seluruh data dan berkas yang kamu isi adalah benar. 
                Setelah formulir dikirimkan, data akan dikunci untuk diperiksa oleh panitia PMB dan tidak dapat diubah kecuali diminta revisi.
            </p>

            <form method="POST" action="{{ route('applicant.application.submit', $application) }}">
                @csrf
                <label class="flex items-start gap-3 p-4 rounded-xl bg-[#F2F5FF]/50 border border-[#E5EAFC] mb-6 cursor-pointer">
                    <input type="checkbox" required class="w-4 h-4 mt-0.5 text-[#5F4AF7] focus:ring-[#5F4AF7] border-gray-300 rounded">
                    <span class="text-xs sm:text-sm text-gray-800 font-medium">
                        Saya menyatakan bahwa seluruh data yang saya isikan adalah benar dan saya bertanggung jawab penuh atas keabsahan dokumen yang diunggah.
                    </span>
                </label>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit"
                        onclick="return confirm('Apakah kamu yakin ingin mengirim formulir pendaftaran ini? Data tidak dapat diubah lagi setelah dikirim.')"
                        class="bg-[#16A34A] hover:bg-[#15803D] text-white px-8 py-3.5 rounded-xl font-semibold text-sm transition shadow-xs inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        <span>Kirim Pendaftaran Sekarang</span>
                    </button>
                    <a href="{{ route('applicant.document.index', $application) }}" 
                        class="bg-white border border-[#E5EAFC] text-[#818CA2] hover:text-black hover:bg-[#F2F5FF] px-6 py-3.5 rounded-xl font-semibold text-sm transition">
                        ← Kembali ke Dokumen
                    </a>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white rounded-2xl p-6 border border-[#E5EAFC] shadow-xs text-center">
            <p class="text-sm text-[#818CA2] mb-3">Pendaftaran ini sudah dikirimkan pada {{ $application->submitted_at?->format('d M Y, H:i') }}.</p>
            <a href="{{ route('applicant.application.status', $application) }}" 
               class="bg-[#5F4AF7] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#4E39E5] transition inline-block">
                Lihat Status Pendaftaran →
            </a>
        </div>
    @endif
</div>
@endsection
