@extends('layouts.app')
@section('title', 'Ringkasan Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Ringkasan Pendaftaran</h1>

    {{-- Data Utama --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <h2 class="font-bold text-blue-700 mb-3">Informasi Pendaftaran</h2>
        <dl class="grid grid-cols-2 gap-2 text-sm">
            <dt class="text-gray-500">Periode</dt><dd class="font-medium">{{ $application->admissionPeriod->name }}</dd>
            <dt class="text-gray-500">Program Studi</dt><dd class="font-medium">{{ $application->studyProgram->name }}</dd>
            <dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $application->full_name }}</dd>
            <dt class="text-gray-500">Jenis Kelamin</dt><dd>{{ $application->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd>
            <dt class="text-gray-500">Tanggal Lahir</dt><dd>{{ $application->birth_date->format('d M Y') }}</dd>
            <dt class="text-gray-500">No. HP</dt><dd>{{ $application->phone }}</dd>
            <dt class="text-gray-500">Sekolah</dt><dd>{{ $application->school_name }}</dd>
            <dt class="text-gray-500">Tahun Lulus</dt><dd>{{ $application->graduation_year }}</dd>
        </dl>
    </div>

    {{-- Dokumen --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <h2 class="font-bold text-blue-700 mb-3">Dokumen yang Diunggah</h2>
        @if($application->documents->isEmpty())
            <p class="text-red-500 text-sm">Belum ada dokumen yang diunggah.</p>
        @else
            <ul class="text-sm space-y-1">
                @foreach($application->documents as $doc)
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✓</span>
                        <span>{{ $doc->label }}</span>
                        <span class="text-gray-400 text-xs">({{ $doc->original_name }})</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Submit --}}
    @if($application->isEditable())
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="font-bold text-blue-700 mb-3">Kirim Pendaftaran</h2>
            <p class="text-sm text-gray-600 mb-4">
                Dengan mengirim pendaftaran, kamu menyatakan bahwa semua data yang diisi adalah benar dan dapat dipertanggungjawabkan.
                Setelah dikirim, data tidak bisa diubah kecuali diminta oleh admin.
            </p>

            <form method="POST" action="{{ route('applicant.application.submit', $application) }}">
                @csrf
                <label class="flex items-start gap-2 mb-4 cursor-pointer">
                    <input type="checkbox" required class="mt-1">
                    <span class="text-sm">Saya menyatakan data yang saya isi adalah benar dan saya bertanggung jawab atas kebenaran informasi tersebut.</span>
                </label>
                <div class="flex gap-3 mt-4 items-center flex-wrap">
                    <button type="submit"
                        onclick="return confirm('Yakin ingin mengirim pendaftaran? Data tidak bisa diubah setelah dikirim.')"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700">
                        Kirim Pendaftaran
                    </button>
                    <a href="{{ route('applicant.document.index', $application) }}"
                        class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                        ← Kelola Dokumen
                    </a>
                    <a href="{{ route('applicant.application.edit', $application) }}"
                        class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                        ← Edit Data
                    </a>
                </div>
            </form>
        </div>
    @else
        <div class="bg-gray-50 border rounded-xl p-6 text-center text-gray-600">
            Pendaftaran sudah dikirim. Cek status di bawah ini.
            <div class="mt-2">
                <a href="{{ route('applicant.application.status', $application) }}" class="text-blue-600 hover:underline">Lihat Status →</a>
            </div>
        </div>
    @endif
</div>
@endsection