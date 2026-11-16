@extends('layouts.admin')
@section('title', 'Mapping Dokumen ke Jalur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Mapping Dokumen</h1>
            <p class="text-sm text-gray-600 mt-1">
                {{ $dokumen->nama }} ({{ $dokumen->kode }}) &mdash; pilih jalur masuk yang mewajibkan dokumen ini.
            </p>
        </div>
        <a href="{{ route('admin.dokumen.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali</a>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ route('admin.dokumen.mapping.simpan', $dokumen) }}">
            @csrf

            <div class="space-y-2">
                @forelse($jalur as $j)
                    <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 hover:border-blue-400 hover:bg-blue-50/40 cursor-pointer">
                        <input type="checkbox" name="jalur[]" value="{{ $j->id }}" class="mt-0.5"
                            {{ in_array($j->id, $terpilih) ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-medium text-gray-800">{{ $j->nama }}</span>
                            <span class="block text-xs text-gray-500">
                                {{ $j->kode }} &middot; {{ $j->punya_biaya_pendaftaran ? 'Berbayar' : 'Gratis' }}
                            </span>
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada jalur masuk.</p>
                @endforelse
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    Simpan Mapping
                </button>
                <a href="{{ route('admin.dokumen.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
