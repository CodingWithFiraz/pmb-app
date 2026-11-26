@extends('layouts.admin')
@section('title', $golongan->exists ? 'Edit Golongan UKT' : 'Tambah Golongan UKT')

@section('content')
@php $isEdit = $golongan->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-6">{{ $isEdit ? 'Edit Golongan UKT' : 'Tambah Golongan UKT' }}</h1>

    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.golongan-ukt.update', $golongan) : route('admin.golongan-ukt.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode <span class="text-red-500">*</span></label>
                <input type="text" name="kode" value="{{ old('kode', $golongan->kode) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('kode') border-red-500 @enderror"
                    placeholder="cth: I" required>
                @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $golongan->nama) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('nama') border-red-500 @enderror"
                    placeholder="cth: Golongan I" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40">{{ old('deskripsi', $golongan->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.golongan-ukt.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
