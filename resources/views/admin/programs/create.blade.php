@extends('layouts.admin')
@section('title', 'Tambah Program Studi')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-6">Tambah Program Studi</h1>
    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ route('admin.program-studi.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode Prodi <span class="text-red-500">*</span></label>
                <input type="text" name="kode" value="{{ old('kode') }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('kode') border-red-500 @enderror"
                    placeholder="cth: TGF" required>
                @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Program Studi <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Fakultas <span class="text-red-500">*</span></label>
                <select name="fakultas_id" class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40" required>
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach($fakultas as $f)
                        <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->nama }}
                        </option>
                    @endforeach
                </select>
                @error('fakultas_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenjang <span class="text-red-500">*</span></label>
                <input type="text" name="jenjang" value="{{ old('jenjang', 'S1') }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40" required>
                @error('jenjang')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Kuota (kosongkan jika tak terbatas)</label>
                <input type="number" name="kuota" value="{{ old('kuota') }}" min="1"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">Simpan</button>
                <a href="{{ route('admin.program-studi.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
