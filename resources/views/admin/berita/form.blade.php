@extends('layouts.admin')
@section('title', $berita->exists ? 'Edit Berita' : 'Tambah Berita')

@section('content')
@php $isEdit = $berita->exists; @endphp

<div class="max-w-2xl mx-auto">
    <h1 class="font-heading text-2xl font-bold tracking-tight text-[#14141F] mb-6">{{ $isEdit ? 'Edit Berita' : 'Tambah Berita' }}</h1>

    <div class="rounded-2xl border border-[#E6E1EC] bg-white p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store') }}" enctype="multipart/form-data">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('judul') border-red-500 @enderror" required>
                @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $berita->slug) }}"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('slug') border-red-500 @enderror"
                    placeholder="Dibuat otomatis dari judul bila dikosongkan">
                @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Upload Gambar (ke folder assets/berita)</label>
                    <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-1.5 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#F5EFFB] file:px-3 file:py-1 file:text-xs file:font-semibold file:text-[#7E22CE]">
                    @error('image_file')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Atau Path / Link Gambar</label>
                    <input type="text" name="image_url" value="{{ old('image_url', $berita->image_url) }}"
                        class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('image_url') border-red-500 @enderror"
                        placeholder="/assets/nama-foto.jpg atau https://...">
                    @error('image_url')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Isi Berita <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="6"
                    class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('deskripsi') border-red-500 @enderror"
                    required>{{ old('deskripsi', $berita->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $berita->author) }}"
                        class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('author') border-red-500 @enderror" required>
                    @error('author')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Publish</label>
                    <input type="datetime-local" name="published_at"
                        value="{{ old('published_at', $berita->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('published_at') border-red-500 @enderror">
                    @error('published_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-[#E6E1EC] rounded-lg bg-white text-[#14141F] px-3 py-2 focus:ring-2 focus:ring-[#BF8AFC]/40 @error('status') border-red-500 @enderror" required>
                        <option value="draft" {{ old('status', $berita->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="publish" {{ old('status', $berita->status) === 'publish' ? 'selected' : '' }}>Publish</option>
                    </select>
                    @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-[#4E566E] mt-1">Status publish tanpa tanggal memakai waktu sekarang.</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#14141F]/85">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.berita.index') }}" class="border border-[#E6E1EC] text-[#4E566E] px-6 py-2 rounded-lg hover:bg-[#FAF8FC]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
