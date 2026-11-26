@extends('layouts.admin')
@section('title', $fakultas->exists ? 'Edit Fakultas' : 'Tambah Fakultas')

@section('content')
@php $isEdit = $fakultas->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-6">{{ $isEdit ? 'Edit Fakultas' : 'Tambah Fakultas' }}</h1>

    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.fakultas.update', $fakultas) : route('admin.fakultas.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode <span class="text-red-500">*</span></label>
                <input type="text" name="kode" value="{{ old('kode', $fakultas->kode) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('kode') border-red-500 @enderror"
                    placeholder="cth: TGP" required>
                @error('kode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Fakultas <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $fakultas->nama) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-[#4E566E] mt-1">Slug URL halaman publik dibuat otomatis dari nama.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40">{{ old('deskripsi', $fakultas->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.fakultas.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
