@extends('layouts.app')
@section('title', 'Edit Program Studi')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Program Studi</h1>
    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ route('admin.program-studi.update', $programStudi) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode</label>
                <input type="text" name="code" value="{{ old('code', $programStudi->code) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" required>
                @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $programStudi->name) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Fakultas</label>
                <input type="text" name="faculty" value="{{ old('faculty', $programStudi->faculty) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('description', $programStudi->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kuota</label>
                <input type="number" name="quota" value="{{ old('quota', $programStudi->quota) }}" min="1"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $programStudi->is_active ? 'checked' : '' }}>
                    <span class="text-sm font-medium">Aktif</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">Update</button>
                <a href="{{ route('admin.program-studi.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection