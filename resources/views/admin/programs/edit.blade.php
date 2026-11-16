@extends('layouts.app')
@section('title', 'Edit Program Studi')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.program-studi.index') }}" class="hover:text-black transition">Program Studi</a>
                <span>/</span>
                <span class="text-[#5F4AF7]">Edit</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Edit Program Studi</h1>
        </div>
        <a href="{{ route('admin.program-studi.index') }}" 
           class="bg-white border border-[#E5EAFC] text-gray-700 hover:text-black hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-sm font-semibold transition inline-flex items-center gap-2">
            <svg class="w-4 h-4 text-[#818CA2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E5EAFC] shadow-xs">
        <form method="POST" action="{{ route('admin.program-studi.update', $programStudi) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Kode Prodi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $programStudi->code) }}"
                        class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm font-mono text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition @error('code') border-red-500 @enderror"
                        required>
                    @error('code')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Nama Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $programStudi->name) }}"
                        class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                        required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Fakultas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="faculty" value="{{ old('faculty', $programStudi->faculty) }}"
                    class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition"
                    required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Deskripsi Program Studi
                </label>
                <textarea name="description" rows="3"
                    class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition">{{ old('description', $programStudi->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                    Kuota Mahasiswa
                </label>
                <input type="number" name="quota" value="{{ old('quota', $programStudi->quota) }}" min="1"
                    class="w-full bg-[#F2F5FF]/50 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#5F4AF7] focus:border-[#5F4AF7] transition">
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer p-3.5 rounded-xl border border-[#E5EAFC] bg-[#F2F5FF]/40 hover:bg-[#F2F5FF] transition">
                    <input type="checkbox" name="is_active" value="1" {{ $programStudi->is_active ? 'checked' : '' }}
                        class="w-4 h-4 text-[#5F4AF7] focus:ring-[#5F4AF7] border-gray-300 rounded">
                    <div>
                        <span class="text-sm font-semibold text-black block">Status Program Studi Aktif</span>
                        <span class="text-xs text-[#818CA2] block">Program studi ini dapat dipilih oleh calon pendaftar.</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-[#E5EAFC] flex items-center gap-3">
                <button type="submit" 
                    class="bg-[#5F4AF7] text-white px-7 py-3 rounded-xl font-semibold text-sm hover:bg-[#4E39E5] transition shadow-xs">
                    Update Program Studi
                </button>
                <a href="{{ route('admin.program-studi.index') }}" 
                    class="bg-white border border-[#E5EAFC] text-[#818CA2] hover:text-black hover:bg-[#F2F5FF] px-6 py-3 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
