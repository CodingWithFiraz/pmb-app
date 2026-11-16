@extends('layouts.admin')
@section('title', $berita->exists ? 'Edit Berita' : 'Tambah Berita')

@section('content')
@php $isEdit = $berita->exists; @endphp

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Berita' : 'Tambah Berita' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror" required>
                @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $berita->slug) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                    placeholder="Dibuat otomatis dari judul bila dikosongkan">
                @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Link Gambar</label>
                <input type="url" name="image_url" value="{{ old('image_url', $berita->image_url) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('image_url') border-red-500 @enderror"
                    placeholder="https://... (opsional)">
                @error('image_url')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Isi Berita <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="6"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                    required>{{ old('deskripsi', $berita->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $berita->author) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('author') border-red-500 @enderror" required>
                    @error('author')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Publish</label>
                    <input type="datetime-local" name="published_at"
                        value="{{ old('published_at', $berita->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('published_at') border-red-500 @enderror">
                    @error('published_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                        <option value="draft" {{ old('status', $berita->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="publish" {{ old('status', $berita->status) === 'publish' ? 'selected' : '' }}>Publish</option>
                    </select>
                    @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">Status publish tanpa tanggal memakai waktu sekarang.</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.berita.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
