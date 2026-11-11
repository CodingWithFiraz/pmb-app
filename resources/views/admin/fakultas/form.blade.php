@extends('layouts.admin')
@section('title', $fakultas->exists ? 'Edit Fakultas' : 'Tambah Fakultas')

@section('content')
@php $isEdit = $fakultas->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Fakultas' : 'Tambah Fakultas' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.fakultas.update', $fakultas) : route('admin.fakultas.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode <span class="text-red-500">*</span></label>
                <input type="text" name="kode" value="{{ old('kode', $fakultas->kode) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('kode') border-red-500 @enderror"
                    placeholder="cth: TGP" required>
                @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Fakultas <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $fakultas->nama) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-500 mt-1">Slug URL halaman publik dibuat otomatis dari nama.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('deskripsi', $fakultas->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.fakultas.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
