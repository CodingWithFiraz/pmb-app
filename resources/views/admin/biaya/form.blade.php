@extends('layouts.admin')
@section('title', $biaya->exists ? 'Edit Biaya Pendaftaran' : 'Tambah Biaya Pendaftaran')

@section('content')
@php $isEdit = $biaya->exists; @endphp

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Biaya Pendaftaran' : 'Tambah Biaya Pendaftaran' }}</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.biaya-pendaftaran.update', $biaya) : route('admin.biaya-pendaftaran.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Periode <span class="text-red-500">*</span></label>
                <select name="periode_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('periode_id') border-red-500 @enderror" required>
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periodeList as $p)
                        <option value="{{ $p->id }}" {{ (int) old('periode_id', $biaya->periode_id) === $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('periode_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jalur Masuk <span class="text-red-500">*</span></label>
                <select name="jalur_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('jalur_id') border-red-500 @enderror" required>
                    <option value="">-- Pilih Jalur --</option>
                    @foreach($jalurList as $j)
                        <option value="{{ $j->id }}" {{ (int) old('jalur_id', $biaya->jalur_id) === $j->id ? 'selected' : '' }}>
                            {{ $j->nama }}
                        </option>
                    @endforeach
                </select>
                @error('jalur_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Nominal (Rp)</label>
                <input type="number" name="nominal" value="{{ old('nominal', $biaya->nominal) }}" min="0" step="1000"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('nominal') border-red-500 @enderror"
                    placeholder="Kosongkan jika gratis">
                @error('nominal')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-500 mt-1">Nominal dikosongkan berarti jalur tersebut gratis pendaftaran.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.biaya-pendaftaran.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
