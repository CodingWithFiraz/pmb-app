@extends('layouts.app')
@section('title', 'Upload Dokumen')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Upload Dokumen</h1>
        <a href="{{ route('applicant.application.summary', $application) }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">
            Lanjut ke Ringkasan →
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-sm text-blue-800">
        Unggah semua dokumen di bawah ini. Format yang diterima: <strong>PDF, JPG, PNG</strong>. Ukuran maksimal: <strong>2 MB per file</strong>.
    </div>

    @if(! $application->isEditable())
        <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-4 mb-6 text-yellow-800">
            ⚠️ Dokumen tidak bisa diubah karena status pendaftaran adalah <strong>{{ $application->status_label }}</strong>.
        </div>
    @endif

    @foreach($requiredDocuments as $type => $label)
        @php $doc = $documents[$type] ?? null; @endphp
        <div class="bg-white shadow rounded-xl p-5 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-semibold">{{ $label }}</div>
                    @if($doc)
                        <div class="text-sm text-gray-500 mt-1">{{ $doc->original_name }}</div>
                        <div class="text-xs text-gray-400">{{ round($doc->file_size / 1024, 1) }} KB · Diunggah {{ $doc->uploaded_at->format('d M Y H:i') }}</div>
                        @if($doc->verification_status === 'approved')
                            <span class="text-xs text-green-600 font-medium">✓ Disetujui</span>
                        @elseif($doc->verification_status === 'rejected')
                            <span class="text-xs text-red-600 font-medium">✗ Ditolak: {{ $doc->admin_note }}</span>
                        @else
                            <span class="text-xs text-gray-500">Menunggu verifikasi</span>
                        @endif
                    @else
                        <div class="text-sm text-red-500 mt-1">Belum diunggah</div>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if($doc)
                        <a href="{{ route('applicant.document.download', $doc) }}"
                            class="text-sm text-blue-600 border border-blue-300 px-3 py-1 rounded hover:bg-blue-50">
                            Lihat
                        </a>
                    @endif
                </div>
            </div>

            @if($application->isEditable())
                <form method="POST" action="{{ route('applicant.document.store', $application) }}"
                    enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="hidden" name="document_type" value="{{ $type }}">
                    <div class="flex gap-2">
                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                            class="flex-1 text-sm border rounded-lg px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <button type="submit"
                            class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                            {{ $doc ? 'Ganti' : 'Upload' }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endforeach

    <div class="flex gap-3 mt-4">
        <a href="{{ route('applicant.application.edit', $application) }}"
            class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50">
            ← Edit Data
        </a>
        <a href="{{ route('applicant.application.summary', $application) }}"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
            Lanjut ke Ringkasan →
        </a>
    </div>
</div>
@endsection