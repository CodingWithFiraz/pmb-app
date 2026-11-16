@extends('layouts.admin')
@section('title', $faq->exists ? 'Edit Pertanyaan' : 'Tambah Pertanyaan')

@section('content')
@php $isEdit = $faq->exists; @endphp

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Pertanyaan' : 'Tambah Pertanyaan' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.faq.update', $faq) : route('admin.faq.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="kategori" value="{{ old('kategori', $faq->kategori) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror"
                    placeholder="cth: Pendaftaran" required>
                @error('kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                <textarea name="pertanyaan" rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('pertanyaan') border-red-500 @enderror"
                    required>{{ old('pertanyaan', $faq->pertanyaan) }}</textarea>
                @error('pertanyaan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jawaban <span class="text-red-500">*</span></label>
                <textarea name="jawaban" rows="5"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('jawaban') border-red-500 @enderror"
                    required>{{ old('jawaban', $faq->jawaban) }}</textarea>
                @error('jawaban')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6 sm:w-1/3">
                <label class="block text-sm font-medium mb-1">Urutan <span class="text-red-500">*</span></label>
                <input type="number" name="urutan" value="{{ old('urutan', $faq->urutan ?? 0) }}" min="0"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('urutan') border-red-500 @enderror" required>
                @error('urutan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.faq.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
