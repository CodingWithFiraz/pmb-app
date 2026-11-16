@extends('layouts.admin')
@section('title', $golongan->exists ? 'Edit Golongan UKT' : 'Tambah Golongan UKT')

@section('content')
@php $isEdit = $golongan->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Golongan UKT' : 'Tambah Golongan UKT' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.golongan-ukt.update', $golongan) : route('admin.golongan-ukt.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode <span class="text-red-500">*</span></label>
                <input type="text" name="kode" value="{{ old('kode', $golongan->kode) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('kode') border-red-500 @enderror"
                    placeholder="cth: I" required>
                @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $golongan->nama) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                    placeholder="cth: Golongan I" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('deskripsi', $golongan->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.golongan-ukt.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
