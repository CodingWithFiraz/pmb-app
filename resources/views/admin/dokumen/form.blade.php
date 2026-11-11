@extends('layouts.admin')
@section('title', $dokumen->exists ? 'Edit Dokumen' : 'Tambah Dokumen')

@section('content')
@php $isEdit = $dokumen->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Dokumen' : 'Tambah Dokumen' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.dokumen.update', $dokumen) : route('admin.dokumen.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $dokumen->kode) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('kode') border-red-500 @enderror"
                        placeholder="cth: KTP" required>
                    @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tipe Berkas <span class="text-red-500">*</span></label>
                    <select name="tipe_file" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('tipe_file') border-red-500 @enderror" required>
                        <option value="pdf" {{ old('tipe_file', $dokumen->tipe_file ?? 'pdf') === 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="jpg" {{ old('tipe_file', $dokumen->tipe_file) === 'jpg' ? 'selected' : '' }}>JPG</option>
                    </select>
                    @error('tipe_file')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Dokumen <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $dokumen->nama) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Ukuran Maksimum (KB) <span class="text-red-500">*</span></label>
                <input type="number" name="max_size" value="{{ old('max_size', $dokumen->max_size ?? 2048) }}" min="100" max="10240"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('max_size') border-red-500 @enderror" required>
                @error('max_size')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_wajib" value="1" {{ old('is_wajib', $dokumen->is_wajib) ? 'checked' : '' }}>
                    <span class="text-sm font-medium">Dokumen wajib diunggah</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.dokumen.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
