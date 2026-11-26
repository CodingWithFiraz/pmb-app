@extends('layouts.admin')
@section('title', 'Tambah Periode PMB')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-6">Tambah Periode PMB</h1>
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ route('admin.periode.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Periode <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('nama') border-red-500 @enderror"
                    placeholder="cth: PMB Gelombang 1 2025/2026" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tahun Akademik <span class="text-red-500">*</span></label>
                <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik') }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40"
                    placeholder="cth: 2025/2026" required>
                @error('tahun_akademik')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                        class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    <span class="text-sm font-medium">Aktif (periode ini menerima pendaftaran)</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">Simpan</button>
                <a href="{{ route('admin.periode.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
