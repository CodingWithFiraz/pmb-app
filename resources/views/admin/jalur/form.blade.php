@extends('layouts.admin')
@section('title', $jalur->exists ? 'Edit Jalur Masuk' : 'Tambah Jalur Masuk')

@section('content')
@php $isEdit = $jalur->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Jalur Masuk' : 'Tambah Jalur Masuk' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.jalur-masuk.update', $jalur) : route('admin.jalur-masuk.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $jalur->kode) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('kode') border-red-500 @enderror"
                        placeholder="cth: SNBP" required>
                    @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Urutan <span class="text-red-500">*</span></label>
                    <input type="number" name="urutan" value="{{ old('urutan', $jalur->urutan ?? 1) }}" min="1"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('urutan') border-red-500 @enderror" required>
                    @error('urutan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Jalur <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $jalur->nama) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('deskripsi', $jalur->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="punya_biaya_pendaftaran" value="1"
                        {{ old('punya_biaya_pendaftaran', $jalur->punya_biaya_pendaftaran) ? 'checked' : '' }}>
                    <span class="text-sm font-medium">Jalur ini dikenakan biaya pendaftaran</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.jalur-masuk.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
