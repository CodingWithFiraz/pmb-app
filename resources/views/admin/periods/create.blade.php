@extends('layouts.app')
@section('title', 'Tambah Periode')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Periode Penerimaan</h1>
    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ route('admin.periode.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Periode <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="cth: PMB Reguler 2025/2026" required>
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tahun Akademik <span class="text-red-500">*</span></label>
                <input type="text" name="academic_year" value="{{ old('academic_year') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="cth: 2025/2026" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Buka <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Tutup <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Biaya Pendaftaran (Rp, 0 = gratis)</label>
                <input type="number" name="registration_fee" value="{{ old('registration_fee', 0) }}"
                    min="0" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="closed">Tutup</option>
                    <option value="open">Buka</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">Simpan</button>
                <a href="{{ route('admin.periode.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection