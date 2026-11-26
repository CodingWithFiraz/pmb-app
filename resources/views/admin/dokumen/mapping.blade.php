@extends('layouts.admin')
@section('title', 'Mapping Dokumen ke Jalur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F]">Mapping Dokumen</h1>
            <p class="text-sm text-[#4E566E] mt-1">
                {{ $dokumen->nama }} ({{ $dokumen->kode }}) &mdash; pilih jalur masuk yang mewajibkan dokumen ini.
            </p>
        </div>
        <a href="{{ route('admin.dokumen.index') }}" class="text-[#7E22CE] hover:underline text-sm">&larr; Kembali</a>
    </div>

    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ route('admin.dokumen.mapping.simpan', $dokumen) }}">
            @csrf

            <div class="space-y-2">
                @forelse($jalur as $j)
                    <label class="flex items-center gap-3 rounded-lg border border-[#E6E1EC] px-4 py-3 hover:border-blue-400 hover:bg-[#F5EFFB]/40 cursor-pointer">
                        <input type="checkbox" name="jalur[]" value="{{ $j->id }}" class="mt-0.5"
                            {{ in_array($j->id, $terpilih) ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-medium text-[#14141F]">{{ $j->nama }}</span>
                            <span class="block text-xs text-[#4E566E]">
                                {{ $j->kode }} &middot; {{ $j->punya_biaya_pendaftaran ? 'Berbayar' : 'Gratis' }}
                            </span>
                        </span>
                    </label>
                @empty
                    <p class="text-[#4E566E] text-sm">Belum ada jalur masuk.</p>
                @endforelse
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">
                    Simpan Mapping
                </button>
                <a href="{{ route('admin.dokumen.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
