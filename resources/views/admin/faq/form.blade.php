@extends('layouts.admin')
@section('title', $faq->exists ? 'Edit Pertanyaan' : 'Tambah Pertanyaan')

@section('content')
@php $isEdit = $faq->exists; @endphp

<div class="max-w-2xl mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-6">{{ $isEdit ? 'Edit Pertanyaan' : 'Tambah Pertanyaan' }}</h1>

    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.faq.update', $faq) : route('admin.faq.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="kategori" value="{{ old('kategori', $faq->kategori) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('kategori') border-red-500 @enderror"
                    placeholder="cth: Pendaftaran" required>
                @error('kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                <textarea name="pertanyaan" rows="2"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('pertanyaan') border-red-500 @enderror"
                    required>{{ old('pertanyaan', $faq->pertanyaan) }}</textarea>
                @error('pertanyaan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jawaban <span class="text-red-500">*</span></label>
                <textarea name="jawaban" rows="5"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('jawaban') border-red-500 @enderror"
                    required>{{ old('jawaban', $faq->jawaban) }}</textarea>
                @error('jawaban')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6 sm:w-1/3">
                <label class="block text-sm font-medium mb-1">Urutan <span class="text-red-500">*</span></label>
                <input type="number" name="urutan" value="{{ old('urutan', $faq->urutan ?? 0) }}" min="0"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('urutan') border-red-500 @enderror" required>
                @error('urutan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.faq.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
